<?php

/* ~ class.smtp.php
  .---------------------------------------------------------------------------.
  |  Software: PHPMailer - PHP email class                                    |
  |   Version: 5.2 (patched for PHP 7.4+)                                    |
  |      Site: https://code.google.com/a/apache-extras.org/p/phpmailer/       |
  '---------------------------------------------------------------------------'
 */

class SMTP {

    public $SMTP_PORT = 25;
    public $CRLF = "\r\n";
    public $do_debug;
    public $do_verp = false;
    public $Version = '5.2';

    private $smtp_conn;
    private $error;
    private $helo_rply;

    public function __construct() {
        $this->smtp_conn = 0;
        $this->error     = null;
        $this->helo_rply = null;
        $this->do_debug  = 0;
    }

    // ── CONNECT ────────────────────────────────────────────────────
    public function Connect($host, $port = 0, $tval = 30) {
        $this->error = null;

        if ($this->connected()) {
            $this->error = array("error" => "Already connected to a server");
            return false;
        }

        if (empty($port)) {
            $port = $this->SMTP_PORT;
        }

        $this->smtp_conn = @fsockopen($host, $port, $errno, $errstr, $tval);

        if (empty($this->smtp_conn)) {
            $this->error = array(
                "error"  => "Failed to connect to server",
                "errno"  => $errno,
                "errstr" => $errstr
            );
            if ($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $this->error["error"] . ": $errstr ($errno)" . $this->CRLF . '<br />';
            }
            return false;
        }

        if (substr(PHP_OS, 0, 3) != "WIN") {
            socket_set_timeout($this->smtp_conn, $tval, 0);
        }

        $announce = $this->get_lines();

        if ($this->do_debug >= 2) {
            echo "SMTP -> FROM SERVER:" . $announce . $this->CRLF . '<br />';
        }

        return true;
    }

    // ── STARTTLS ───────────────────────────────────────────────────
    public function StartTLS() {
        $this->error = null;

        if (!$this->connected()) {
            $this->error = array("error" => "Called StartTLS() without being connected");
            return false;
        }

        fputs($this->smtp_conn, "STARTTLS" . $this->CRLF);
        $rply = $this->get_lines();
        $code = substr($rply, 0, 3);

        if ($this->do_debug >= 2) {
            echo "SMTP -> FROM SERVER:" . $rply . $this->CRLF . '<br />';
        }

        if ($code != 220) {
            $this->error = array(
                "error"    => "STARTTLS not accepted from server",
                "smtp_code" => $code,
                "smtp_msg"  => substr($rply, 4)
            );
            if ($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply . $this->CRLF . '<br />';
            }
            return false;
        }

        if (!stream_socket_enable_crypto($this->smtp_conn, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
            return false;
        }

        return true;
    }

    // ── AUTHENTICATE ───────────────────────────────────────────────
    public function Authenticate($username, $password) {
        fputs($this->smtp_conn, "AUTH LOGIN" . $this->CRLF);
        $rply = $this->get_lines();
        $code = substr($rply, 0, 3);

        if ($code != 334) {
            $this->error = array(
                "error"    => "AUTH not accepted from server",
                "smtp_code" => $code,
                "smtp_msg"  => substr($rply, 4)
            );
            if ($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply . $this->CRLF . '<br />';
            }
            return false;
        }

        fputs($this->smtp_conn, base64_encode($username) . $this->CRLF);
        $rply = $this->get_lines();
        $code = substr($rply, 0, 3);

        if ($code != 334) {
            $this->error = array(
                "error"    => "Username not accepted from server",
                "smtp_code" => $code,
                "smtp_msg"  => substr($rply, 4)
            );
            if ($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply . $this->CRLF . '<br />';
            }
            return false;
        }

        fputs($this->smtp_conn, base64_encode($password) . $this->CRLF);
        $rply = $this->get_lines();
        $code = substr($rply, 0, 3);

        if ($code != 235) {
            $this->error = array(
                "error"    => "Password not accepted from server",
                "smtp_code" => $code,
                "smtp_msg"  => substr($rply, 4)
            );
            if ($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply . $this->CRLF . '<br />';
            }
            return false;
        }

        return true;
    }

    // ── CONNECTED ──────────────────────────────────────────────────
    public function Connected() {
        if (!empty($this->smtp_conn)) {
            $sock_status = socket_get_status($this->smtp_conn);
            if ($sock_status["eof"]) {
                if ($this->do_debug >= 1) {
                    echo "SMTP -> NOTICE:" . $this->CRLF . "EOF caught while checking if connected";
                }
                $this->Close();
                return false;
            }
            return true;
        }
        return false;
    }

    // ── CLOSE ──────────────────────────────────────────────────────
    public function Close() {
        $this->error     = null;
        $this->helo_rply = null;
        if (!empty($this->smtp_conn)) {
            fclose($this->smtp_conn);
            $this->smtp_conn = 0;
        }
    }

    // ── DATA ───────────────────────────────────────────────────────
    // FIX: replaced deprecated each() with foreach (PHP 7.4+ compatible)
    public function Data($msg_data) {
        $this->error = null;

        if (!$this->connected()) {
            $this->error = array("error" => "Called Data() without being connected");
            return false;
        }

        fputs($this->smtp_conn, "DATA" . $this->CRLF);
        $rply = $this->get_lines();
        $code = substr($rply, 0, 3);

        if ($this->do_debug >= 2) {
            echo "SMTP -> FROM SERVER:" . $rply . $this->CRLF . '<br />';
        }

        if ($code != 354) {
            $this->error = array(
                "error"    => "DATA command not accepted from server",
                "smtp_code" => $code,
                "smtp_msg"  => substr($rply, 4)
            );
            if ($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply . $this->CRLF . '<br />';
            }
            return false;
        }

        // Normalize line breaks
        $msg_data = str_replace("\r\n", "\n", $msg_data);
        $msg_data = str_replace("\r",   "\n", $msg_data);
        $lines    = explode("\n", $msg_data);

        $field      = substr($lines[0], 0, strpos($lines[0], ":"));
        $in_headers = (!empty($field) && !strstr($field, " "));

        $max_line_length = 998;

        // ── FIX: was while(list(,$line) = @each($lines)) ──────────
        foreach ($lines as $line) {
            $lines_out = array();
            if ($line == "" && $in_headers) {
                $in_headers = false;
            }
            while (strlen($line) > $max_line_length) {
                $pos = strrpos(substr($line, 0, $max_line_length), " ");
                if (!$pos) {
                    $pos        = $max_line_length - 1;
                    $lines_out[] = substr($line, 0, $pos);
                    $line        = substr($line, $pos);
                } else {
                    $lines_out[] = substr($line, 0, $pos);
                    $line        = substr($line, $pos + 1);
                }
                if ($in_headers) {
                    $line = "\t" . $line;
                }
            }
            $lines_out[] = $line;

            // ── FIX: was while(list(,$line_out) = @each($lines_out)) ──
            foreach ($lines_out as $line_out) {
                if (strlen($line_out) > 0) {
                    if (substr($line_out, 0, 1) == ".") {
                        $line_out = "." . $line_out;
                    }
                }
                fputs($this->smtp_conn, $line_out . $this->CRLF);
            }
        }

        fputs($this->smtp_conn, $this->CRLF . "." . $this->CRLF);

        $rply = $this->get_lines();
        $code = substr($rply, 0, 3);

        if ($this->do_debug >= 2) {
            echo "SMTP -> FROM SERVER:" . $rply . $this->CRLF . '<br />';
        }

        if ($code != 250) {
            $this->error = array(
                "error"    => "DATA not accepted from server",
                "smtp_code" => $code,
                "smtp_msg"  => substr($rply, 4)
            );
            if ($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply . $this->CRLF . '<br />';
            }
            return false;
        }
        return true;
    }

    // ── HELLO ──────────────────────────────────────────────────────
    public function Hello($host = '') {
        $this->error = null;

        if (!$this->connected()) {
            $this->error = array("error" => "Called Hello() without being connected");
            return false;
        }

        if (empty($host)) {
            $host = "localhost";
        }

        if (!$this->SendHello("EHLO", $host)) {
            if (!$this->SendHello("HELO", $host)) {
                return false;
            }
        }

        return true;
    }

    private function SendHello($hello, $host) {
        fputs($this->smtp_conn, $hello . " " . $host . $this->CRLF);
        $rply = $this->get_lines();
        $code = substr($rply, 0, 3);

        if ($this->do_debug >= 2) {
            echo "SMTP -> FROM SERVER: " . $rply . $this->CRLF . '<br />';
        }

        if ($code != 250) {
            $this->error = array(
                "error"    => $hello . " not accepted from server",
                "smtp_code" => $code,
                "smtp_msg"  => substr($rply, 4)
            );
            if ($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply . $this->CRLF . '<br />';
            }
            return false;
        }

        $this->helo_rply = $rply;
        return true;
    }

    // ── MAIL FROM ──────────────────────────────────────────────────
    public function Mail($from) {
        $this->error = null;

        if (!$this->connected()) {
            $this->error = array("error" => "Called Mail() without being connected");
            return false;
        }

        $useVerp = ($this->do_verp ? "XVERP" : "");
        fputs($this->smtp_conn, "MAIL FROM:<" . $from . ">" . $useVerp . $this->CRLF);

        $rply = $this->get_lines();
        $code = substr($rply, 0, 3);

        if ($this->do_debug >= 2) {
            echo "SMTP -> FROM SERVER:" . $rply . $this->CRLF . '<br />';
        }

        if ($code != 250) {
            $this->error = array(
                "error"    => "MAIL not accepted from server",
                "smtp_code" => $code,
                "smtp_msg"  => substr($rply, 4)
            );
            if ($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply . $this->CRLF . '<br />';
            }
            return false;
        }
        return true;
    }

    // ── QUIT ───────────────────────────────────────────────────────
    public function Quit($close_on_error = true) {
        $this->error = null;

        if (!$this->connected()) {
            $this->error = array("error" => "Called Quit() without being connected");
            return false;
        }

        fputs($this->smtp_conn, "quit" . $this->CRLF);
        $byemsg = $this->get_lines();

        if ($this->do_debug >= 2) {
            echo "SMTP -> FROM SERVER:" . $byemsg . $this->CRLF . '<br />';
        }

        $rval = true;
        $e    = null;
        $code = substr($byemsg, 0, 3);

        if ($code != 221) {
            $e    = array(
                "error"     => "SMTP server rejected quit command",
                "smtp_code" => $code,
                "smtp_rply" => substr($byemsg, 4)
            );
            $rval = false;
            if ($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $e["error"] . ": " . $byemsg . $this->CRLF . '<br />';
            }
        }

        if (empty($e) || $close_on_error) {
            $this->Close();
        }

        return $rval;
    }

    // ── RECIPIENT ──────────────────────────────────────────────────
    public function Recipient($to) {
        $this->error = null;

        if (!$this->connected()) {
            $this->error = array("error" => "Called Recipient() without being connected");
            return false;
        }

        fputs($this->smtp_conn, "RCPT TO:<" . $to . ">" . $this->CRLF);
        $rply = $this->get_lines();
        $code = substr($rply, 0, 3);

        if ($this->do_debug >= 2) {
            echo "SMTP -> FROM SERVER:" . $rply . $this->CRLF . '<br />';
        }

        if ($code != 250 && $code != 251) {
            $this->error = array(
                "error"    => "RCPT not accepted from server",
                "smtp_code" => $code,
                "smtp_msg"  => substr($rply, 4)
            );
            if ($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply . $this->CRLF . '<br />';
            }
            return false;
        }
        return true;
    }

    // ── RESET ──────────────────────────────────────────────────────
    public function Reset() {
        $this->error = null;

        if (!$this->connected()) {
            $this->error = array("error" => "Called Reset() without being connected");
            return false;
        }

        fputs($this->smtp_conn, "RSET" . $this->CRLF);
        $rply = $this->get_lines();
        $code = substr($rply, 0, 3);

        if ($this->do_debug >= 2) {
            echo "SMTP -> FROM SERVER:" . $rply . $this->CRLF . '<br />';
        }

        if ($code != 250) {
            $this->error = array(
                "error"    => "RSET failed",
                "smtp_code" => $code,
                "smtp_msg"  => substr($rply, 4)
            );
            if ($this->do_debug >= 1) {
                echo "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply . $this->CRLF . '<br />';
            }
            return false;
        }

        return true;
    }

    // ── GET ERROR ──────────────────────────────────────────────────
    public function getError() {
        return $this->error;
    }

    // ── GET LINES (internal) ───────────────────────────────────────
    private function get_lines() {
        $data = "";
        while ($str = @fgets($this->smtp_conn, 515)) {
            if ($this->do_debug >= 4) {
                echo "SMTP -> get_lines(): \$data was \"$data\"" . $this->CRLF . '<br />';
                echo "SMTP -> get_lines(): \$str is \"$str\""   . $this->CRLF . '<br />';
            }
            $data .= $str;
            if ($this->do_debug >= 4) {
                echo "SMTP -> get_lines(): \$data is \"$data\"" . $this->CRLF . '<br />';
            }
            if (substr($str, 3, 1) == " ") {
                break;
            }
        }
        return $data;
    }
}