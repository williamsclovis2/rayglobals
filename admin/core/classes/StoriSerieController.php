<?php

/**
 * StoriSerieController.php
 *
 * Handles add / edit / state changes / view tracking / pin-on-top
 * for job vacancy listings (stori_serie table).
 *
 * New fields vs. original:
 *  - category        : job category for sidebar filter
 *  - location        : physical city/region for sidebar filter
 *  - experience_range: e.g. "1-2", "2-3", "3-6", "6+" for sidebar filter
 */
class StoriSerieController
{
    /* ------------------------------------------------------------------ */
    /*  Constants                                                          */
    /* ------------------------------------------------------------------ */

    const MAX_COMPETENCES = 7;
    const MAX_EXPERIENCES = 4;

    const ALLOWED_IMAGE_TYPES = [
        'image/jpeg', 'image/pjpeg', 'image/jpg',
        'image/png', 'image/gif',
    ];

    const UPLOAD_DIR = 'media_data/stori/';

    /** Valid job categories — must match form options */
    const VALID_CATEGORIES = [
        'informatique', 'finance', 'marketing', 'sante',
        'education', 'construction', 'agriculture', 'autre',
    ];

    /** Valid experience ranges — must match form options */
    const VALID_EXPERIENCE_RANGES = ['1-2', '2-3', '3-6', '6+', ''];

    /* ------------------------------------------------------------------ */
    /*  Public: Add a new vacancy                                          */
    /* ------------------------------------------------------------------ */

    public static function add(): object
    {
        $data = self::parsePrefixedPost('register-');

        $validate   = new Validate();
        $validation = $validate->check($data, self::buildValidationRules(false));

        if (!$validation->passed()) {
            return self::errorResponse($validate->getErrorLocation());
        }

        $competences = self::extractDynamicList($data, 'competences', self::MAX_COMPETENCES);
        $experiences = self::extractDynamicList($data, 'experiences',  self::MAX_EXPERIENCES);

        if (empty($competences)) {
            return self::errorResponse(['competences' => ['Au moins une compétence est requise.']]);
        }
        if (empty($experiences)) {
            return self::errorResponse(['experiences' => ['Au moins une expérience est requise.']]);
        }

        $uploadResult = self::handleUpload(true);
        if ($uploadResult['error']) {
            Session::put('errors', $uploadResult['message']);
            return self::errorResponse(['featuredImage' => [$uploadResult['message']]]);
        }

        $pin_top = (int) self::getLastPin() + 1;

        $payload = [
            'photo'               => $uploadResult['path'],
            'serie_title'         => self::sanitise($data['serie_title']),
            'company_name'        => self::sanitise($data['company_name']),
            'job_type'            => self::sanitise($data['job_type']),
            'category'            => self::sanitiseEnum($data['category']         ?? '', self::VALID_CATEGORIES),
            'location'            => self::sanitise($data['location']             ?? ''),
            'experience_range'    => self::sanitiseEnum($data['experience_range'] ?? '', self::VALID_EXPERIENCE_RANGES),
            'education'           => self::sanitise($data['education']),
            'serie_description'   => self::sanitise($data['serie_description']),
            'dtserie_description' => self::sanitise($data['dtserie_description'] ?? ''),
            'package'             => self::sanitise($data['package']),
            'package_type'        => self::sanitise($data['package_type']),
            'language'            => self::sanitise($data['language'] ?? 'KINYARWANDA'),
            'pin_top'             => $pin_top,
            'state'               => 'Pending',
        ];

        for ($i = 1; $i <= self::MAX_COMPETENCES; $i++) {
            $payload["comp_{$i}"] = $competences[$i - 1] ?? null;
        }
        for ($i = 1; $i <= self::MAX_EXPERIENCES; $i++) {
            $payload["exp_{$i}"] = $experiences[$i - 1] ?? null;
        }

        try {
            (new StoriSerie())->insert($payload);
            Session::put('success', 'L\'offre d\'emploi a été enregistrée avec succès !');
            return self::successResponse();
        } catch (Exception $e) {
            error_log('[StoriSerieController::add] ' . $e->getMessage());
            return self::errorResponse(['_db' => [$e->getMessage()]]);
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Public: Edit an existing vacancy                                   */
    /* ------------------------------------------------------------------ */

    public static function edit(object $stori_serie_data): object
    {
        $serie_ID = (int) $stori_serie_data->ID;

        $data = self::parsePrefixedPost('register-');

        $validate   = new Validate();
        $validation = $validate->check($data, self::buildValidationRules(true));

        if (!$validation->passed()) {
            return self::errorResponse($validate->getErrorLocation());
        }

        $competences = self::extractDynamicList($data, 'competences', self::MAX_COMPETENCES);
        $experiences = self::extractDynamicList($data, 'experiences',  self::MAX_EXPERIENCES);

        if (empty($competences)) {
            return self::errorResponse(['competences' => ['Au moins une compétence est requise.']]);
        }
        if (empty($experiences)) {
            return self::errorResponse(['experiences' => ['Au moins une expérience est requise.']]);
        }

        $uploadResult = self::handleUpload(false);
        if ($uploadResult['error']) {
            Session::put('errors', $uploadResult['message']);
            return self::errorResponse(['featuredImage' => [$uploadResult['message']]]);
        }

        $photo = $uploadResult['path'] ?: $stori_serie_data->photo;

        $payload = [
            'photo'               => $photo,
            'serie_title'         => self::sanitise($data['serie_title']),
            'company_name'        => self::sanitise($data['company_name']),
            'job_type'            => self::sanitise($data['job_type']),
            'category'            => self::sanitiseEnum($data['category']         ?? '', self::VALID_CATEGORIES),
            'location'            => self::sanitise($data['location']             ?? ''),
            'experience_range'    => self::sanitiseEnum($data['experience_range'] ?? '', self::VALID_EXPERIENCE_RANGES),
            'education'           => self::sanitise($data['education']),
            'serie_description'   => self::sanitise($data['serie_description']),
            'dtserie_description' => self::sanitise($data['dtserie_description'] ?? ''),
            'package'             => self::sanitise($data['package']),
            'package_type'        => self::sanitise($data['package_type']),
            'language'            => self::sanitise($data['language'] ?? 'KINYARWANDA'),
            'updated_date'        => Dates::get('Y-m-d'),
        ];

        for ($i = 1; $i <= self::MAX_COMPETENCES; $i++) {
            $payload["comp_{$i}"] = $competences[$i - 1] ?? null;
        }
        for ($i = 1; $i <= self::MAX_EXPERIENCES; $i++) {
            $payload["exp_{$i}"] = $experiences[$i - 1] ?? null;
        }

        try {
            (new StoriSerie())->update($payload, $serie_ID);
            Session::put('success', 'L\'offre d\'emploi a été mise à jour avec succès !');
            return self::successResponse();
        } catch (Exception $e) {
            error_log('[StoriSerieController::edit] ' . $e->getMessage());
            return self::errorResponse(['_db' => [$e->getMessage()]]);
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Public: Change state                                               */
    /* ------------------------------------------------------------------ */

    public static function changeState(string $state, int $serie_ID): object
    {
        $allowedStates = ['Published', 'Pending', 'Deleted'];
        if (!in_array($state, $allowedStates, true)) {
            return self::errorResponse(['state' => ['État non valide.']]);
        }

        $seconds         = Config::get('time/seconds');
        $storiSerieTable = new StoriSerie();
        $payload         = [];

        switch ($state) {
            case 'Published':
                $payload = [
                    'state'        => 'Published',
                    'posting_date' => Dates::convTo('date', $seconds),
                    'posting_time' => Dates::convTo('time', $seconds),
                    'pin_top'      => (int) self::getLastPin() + 1,
                ];
                break;
            case 'Pending':
                $payload = ['state' => 'Pending'];
                break;
            case 'Deleted':
                $payload = ['state' => 'Deleted'];
                break;
        }

        try {
            $storiSerieTable->update($payload, $serie_ID);
            return self::successResponse();
        } catch (Exception $e) {
            error_log('[StoriSerieController::changeState] ' . $e->getMessage());
            return self::errorResponse(['_db' => [$e->getMessage()]]);
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Public: Add a view                                                 */
    /* ------------------------------------------------------------------ */

    public static function addView(int $serie_ID): void
    {
        $table = new StoriSerie();
        $table->selectQuery('SELECT `views` FROM `stori_serie` WHERE `ID` = ?', [$serie_ID]);

        if (!$table->count()) return;

        $new_views = (int) $table->first()->views + 1;
        $seconds   = Config::get('time/seconds');

        try {
            $table->update([
                'views'          => $new_views,
                'last_view_date' => Dates::convTo('date', $seconds),
                'last_view_time' => Dates::convTo('time', $seconds),
            ], $serie_ID);
        } catch (Exception $e) {
            error_log('[StoriSerieController::addView] ' . $e->getMessage());
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Public: Pin on top                                                 */
    /* ------------------------------------------------------------------ */

    public static function pinOnTop(int $serie_ID): void
    {
        $pin_top = (int) self::getLastPin() + 1;
        try {
            (new StoriSerie())->update(['pin_top' => $pin_top], $serie_ID);
        } catch (Exception $e) {
            error_log('[StoriSerieController::pinOnTop] ' . $e->getMessage());
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Public: Get highest pin_top value                                  */
    /* ------------------------------------------------------------------ */

    public static function getLastPin(): int
    {
        $table = new StoriSerie();
        $table->selectQuery('SELECT `pin_top` FROM `stori_serie` ORDER BY `pin_top` DESC LIMIT 1');

        if ($table->count() && ($first = $table->first()) && isset($first->pin_top)) {
            return (int) $first->pin_top;
        }
        return 0;
    }

    /* ------------------------------------------------------------------ */
    /*  Public: Build a filtered query for the vacancies list page        */
    /* ------------------------------------------------------------------ */

    /**
     * Returns ['sql' => string, 'params' => array, 'count' => int]
     * Usage in vacancies.php:
     *   $result = StoriSerieController::buildFilteredQuery($_GET);
     *   $storiSerieTable->selectQuery($result['sql'], $result['params']);
     */
    public static function buildFilteredQuery(array $get): array
    {
        $where  = ["state = 'Published'"];
        $params = [];

        // Job type filter (checkbox array)
        if (!empty($get['job_type']) && is_array($get['job_type'])) {
            $safe  = array_filter($get['job_type'], fn($v) => preg_match('/^[a-z]+$/', $v));
            if ($safe) {
                $ph     = implode(',', array_fill(0, count($safe), '?'));
                $where[]= "job_type IN ($ph)";
                $params = array_merge($params, array_values($safe));
            }
        }

        // Category filter
        if (!empty($get['category']) && in_array($get['category'], self::VALID_CATEGORIES, true)) {
            $where[]  = 'category = ?';
            $params[] = $get['category'];
        }

        // Location filter
        if (!empty($get['location'])) {
            $where[]  = 'location LIKE ?';
            $params[] = '%' . $get['location'] . '%';
        }

        // Experience range filter (checkbox array)
        if (!empty($get['experience_range']) && is_array($get['experience_range'])) {
            $safe = array_filter($get['experience_range'], fn($v) => in_array($v, self::VALID_EXPERIENCE_RANGES, true));
            if ($safe) {
                $ph     = implode(',', array_fill(0, count($safe), '?'));
                $where[]= "experience_range IN ($ph)";
                $params = array_merge($params, array_values($safe));
            }
        }

        // "Posted within N days" filter
        if (!empty($get['posted_within']) && ctype_digit((string) $get['posted_within'])) {
            $days     = (int) $get['posted_within'];
            $where[]  = 'posting_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)';
            $params[] = $days;
        }

        $sql = 'SELECT * FROM `stori_serie` WHERE '
             . implode(' AND ', $where)
             . ' ORDER BY pin_top DESC';

        return ['sql' => $sql, 'params' => $params];
    }

    /* ------------------------------------------------------------------ */
    /*  Private helpers                                                    */
    /* ------------------------------------------------------------------ */

    private static function parsePrefixedPost(string $prefix): array
    {
        $result = [];
        foreach ($_POST as $key => $val) {
            if (strpos($key, $prefix) === 0) {
                $cleanKey          = substr($key, strlen($prefix));
                $result[$cleanKey] = $val;
            }
        }
        return $result;
    }

    private static function extractDynamicList(array $data, string $key, int $max): array
    {
        if (empty($data[$key]) || !is_array($data[$key])) return [];

        $items = [];
        foreach ($data[$key] as $item) {
            $clean = trim((string) $item);
            if ($clean !== '') {
                $items[] = htmlspecialchars($clean, ENT_QUOTES, 'UTF-8');
            }
            if (count($items) >= $max) break;
        }
        return $items;
    }

    private static function handleUpload(bool $required): array
    {
        $noFile = empty($_FILES['featuredImage']['name']);

        if ($noFile) {
            if ($required) {
                return ['error' => true, 'path' => '', 'message' => 'Le logo de l\'entreprise est obligatoire.'];
            }
            return ['error' => false, 'path' => '', 'message' => ''];
        }

        $mimeType = strtolower($_FILES['featuredImage']['type']);
        if (!in_array($mimeType, self::ALLOWED_IMAGE_TYPES, true)) {
            return ['error' => true, 'path' => '', 'message' => 'Format d\'image non supporté. Utilisez PNG, JPG ou GIF.'];
        }

        $filename = 'serie_' . substr(md5(uniqid('', true)), -8) . '.jpg';
        $uploaded = FileManager::upload($_FILES['featuredImage'], $filename, self::UPLOAD_DIR);

        if (!$uploaded) {
            return ['error' => true, 'path' => '', 'message' => 'Erreur lors du téléversement de l\'image.'];
        }

        return ['error' => false, 'path' => $uploaded, 'message' => ''];
    }

    private static function buildValidationRules(bool $isEdit): array
    {
        return [
            'serie_title'        => ['name' => 'Titre du poste',      'required' => true,  'max' => 150],
            'company_name'       => ['name' => "Nom de l'entreprise",  'required' => true,  'max' => 150],
            'job_type'           => ['name' => "Type d'emploi",        'required' => true],
            'category'           => ['name' => 'Catégorie',            'required' => true],
            'location'           => ['name' => 'Localisation',         'required' => true,  'max' => 150],
            'experience_range'   => ['name' => 'Expérience'],
            'education'          => ['name' => 'Éducation',            'required' => true,  'max' => 250],
            'serie_description'  => ['name' => 'Courte description',   'required' => true,  'max' => 300],
            'dtserie_description'=> ['name' => 'Description détaillée'],
            'package'            => ['name' => 'Emplacement',          'required' => true],
            'package_type'       => ['name' => 'Visibilité',           'required' => true],
            'language'           => ['name' => 'Langue'],
        ];
    }

    /** Sanitise a value that must be one of a fixed set; returns '' if not valid */
    private static function sanitiseEnum(string $value, array $allowed): string
    {
        $v = trim($value);
        return in_array($v, $allowed, true) ? $v : '';
    }

    private static function sanitise(string $value): string
    {
        return trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
    }

    private static function errorResponse(array $errorScript = []): object
    {
        return (object) ['ERRORS' => true, 'SUCCESS' => false, 'ERRORS_SCRIPT' => $errorScript];
    }

    private static function successResponse(): object
    {
        return (object) ['ERRORS' => false, 'SUCCESS' => true, 'ERRORS_SCRIPT' => []];
    }
}