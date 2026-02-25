<div class="single-job-items mb-30">
    <div class="job-items">
        <div class="company-img">
            <a href="<?=DN?>/vacancies/<?=Hash::encryptToken( $stori_serie_data->ID )?>"><img src="<?=DNADMIN.'/'.$stori_serie_data->photo?>" alt=""></a>
        </div>
        <div class="job-tittle job-tittle2">
            <a href="#">
                <h4><?= $stori_serie_data->serie_title ?></h4>
            </a>
            <ul>
                <li><?= $stori_serie_data->company_name ?></li>
                <li><i class="fas fa-map-marker-alt"></i>Congo, Kinshasa</li>
                <li>$3500 - $4000</li>
            </ul>
        </div>
    </div>
    <div class="items-link items-link2 f-right">
        <a id="" href="<?=DN?>/vacancies/<?=Hash::encryptToken( $stori_serie_data->ID )?>"><?= $stori_serie_data->job_type ?></a>
        <span>Date d'ajout <?= $stori_serie_data->posting_date ?></span>
    </div>
</div>