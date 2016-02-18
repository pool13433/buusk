<?php
//--------------------------------------------------------------------------------------------------
// Utilities for our event-fetching scripts.
//
// Requires PHP 5.2.0 or higher.
//--------------------------------------------------------------------------------------------------
// PHP will fatal error if we attempt to use the DateTime class without this being set.
date_default_timezone_set('UTC');

class Event {

    // Tests whether the given ISO8601 string has a time-of-day or not
    const ALL_DAY_REGEX = '/^\d{4}-\d\d-\d\d$/'; // matches strings like "2013-12-29"

    public $title;
    public $allDay; // a boolean
    public $start; // a DateTime
    public $end; // a DateTime, or null
    public $properties = array(); // an array of other misc properties

    // Constructs an Event object from the given array of key=>values.
    // You can optionally force the timezone of the parsed dates.

    public function __construct($array, $timezone = null) {

        $this->title = $array['title'];

        if (isset($array['allDay'])) {
            // allDay has been explicitly specified
            $this->allDay = (bool) $array['allDay'];
        } else {
            // Guess allDay based off of ISO8601 date strings
            $this->allDay = preg_match(self::ALL_DAY_REGEX, $array['start']) &&
                    (!isset($array['end']) || preg_match(self::ALL_DAY_REGEX, $array['end']));
        }

        if ($this->allDay) {
            // If dates are allDay, we want to parse them in UTC to avoid DST issues.
            $timezone = null;
        }

        // Parse dates
        $this->start = parseDateTime($array['start'], $timezone);
        $this->end = isset($array['end']) ? parseDateTime($array['end'], $timezone) : null;

        // Record misc properties
        foreach ($array as $name => $value) {
            if (!in_array($name, array('title', 'allDay', 'start', 'end'))) {
                $this->properties[$name] = $value;
            }
        }
    }

    // Returns whether the date range of our event intersects with the given all-day range.
    // $rangeStart and $rangeEnd are assumed to be dates in UTC with 00:00:00 time.
    public function isWithinDayRange($rangeStart, $rangeEnd) {

        // Normalize our event's dates for comparison with the all-day range.
        $eventStart = stripTime($this->start);
        $eventEnd = isset($this->end) ? stripTime($this->end) : null;

        if (!$eventEnd) {
            // No end time? Only check if the start is within range.
            return $eventStart < $rangeEnd && $eventStart >= $rangeStart;
        } else {
            // Check if the two ranges intersect.
            return $eventStart < $rangeEnd && $eventEnd > $rangeStart;
        }
    }

    // Converts this Event object back to a plain data array, to be used for generating JSON
    public function toArray() {

        // Start with the misc properties (don't worry, PHP won't affect the original array)
        $array = $this->properties;

        $array['title'] = $this->title;

        // Figure out the date format. This essentially encodes allDay into the date string.
        if ($this->allDay) {
            $format = 'Y-m-d'; // output like "2013-12-29"
        } else {
            $format = 'c'; // full ISO8601 output, like "2013-12-29T09:00:00+08:00"
        }

        // Serialize dates into strings
        $array['start'] = $this->start->format($format);
        if (isset($this->end)) {
            $array['end'] = $this->end->format($format);
        }

        return $array;
    }

}

// Date Utilities
//----------------------------------------------------------------------------------------------
// Parses a string into a DateTime object, optionally forced into the given timezone.
function parseDateTime($string, $timezone = null) {
    $date = new DateTime(
            $string, $timezone ? $timezone : new DateTimeZone('UTC')
            // Used only when the string is ambiguous.
            // Ignored if string has a timezone offset in it.
    );
    if ($timezone) {
        // If our timezone was ignored above, force it.
        $date->setTimezone($timezone);
    }
    return $date;
}

// Takes the year/month/date values of the given DateTime and converts them to a new DateTime,
// but in UTC.
function stripTime($datetime) {
    return new DateTime($datetime->format('Y-m-d'));
}

function pagination($start, $url) {
    var_dump($url);
    ?>
    <nav>
        <ul class="pagination">
            <?php if ($start > 1) { ?>
                <li>
                    <a href="<?= $url . '&start=' . ($start - 1) ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php } ?>

            <li><a href="<?= $url . '&start=' . $start ?>">1</a></li>                        
            <li>
                <a href="<?= $url . '&start=' . ($start + 1) ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
    <?php
}

function sendMail() {
    if (!empty($_POST)) {
        // ##################send email ################
        Yii::import('application.extensions.phpmailer.JPhpMailer');

        $subject = $_POST['topic'];
        //$fromEmail = "thaismilesoft.com@gmail.com"; //"poolsawatapin@gmail.com";
        $fromEmail = $_POST['email']; //"thaismilesoft.com@gmail.com"; //"poolsawatapin@gmail.com";
        $message = 'ชื่อผู้ติดต่อ ' . $_POST['name'] . '<br/>';
        $message .= ' รายละเอียด ' . $_POST['message'];
        $fromName = "จาก เมนูติดต่อเรา ของเว็บ ThaiSmilesoft.com";
        $toName = "Admin (Admin)";
        //$toEmailGmail = 'poolsawatapin@gmail.com';
        $toEmailGmail = 'poon_mp@hotmail.com';
        //$toEmailHotmail = 'poon_mp@hotmail.com';
        // ############# config mail ################
        $mail = new JPhpMailer;
        $mail->IsSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->SMTPDebug = 0; // enables SMTP debug information (for testing)

        /* $mail->Host = 'smtp.googlemail.com:465';
          //$mail->Host = 'smtp.shoparaidee.com:465';
          $mail->SMTPSecure = "ssl";
          //$mail->Host = 'smpt.163.com';
          $mail->SMTPAuth = true;
          $mail->Username = 'poolsawatapin@gmail.com';
          $mail->Password = '0878356866'; */

        $mail->Host = "mail.thaismilesoft.com"; // SMTP server example
        $mail->SMTPDebug = 0; // enables SMTP debug information (for testing)
        $mail->SMTPAuth = true; // enable SMTP authentication
        $mail->Port = 25; // set the SMTP port for the GMAIL server
        $mail->Username = "mailer@thaismilesoft.com"; // SMTP account username example
        $mail->Password = "3NwtoxC2"; // SMTP account password example

        $mail->From = $fromEmail;
        $mail->FromName = $fromName;
        //$mail->SetFrom($fromEmail, $fromName);
        $mail->Subject = $subject;
        $mail->AltBody = $message;
        $mail->WordWrap = 50;
        $mail->MsgHTML($message);
        $mail->IsHTML(true);
        // ############# config mail ################           
        $mail->AddAddress($toEmailGmail, $toName);
        $mail->AddAddress('thaismilesoft.com@gmail.com', 'ThaiSmilesoft.com');
        $mail->AddAddress('thanatta.mankong@gmail.com', 'Thanatta Monkong');
        ############ send email #################                 
        if ($mail->Send()):
            echo JsonUtils::returnJson('success', 'สถานะ ส่งเมลล์', 'ส่งเมลล์สำเร็จ', '');
        else:
            echo JsonUtils::returnJson('error', 'สถานะ ส่งเมลล์', 'ส่งเมลล์ ไม่ได้', '');
        endif;
    }
}

function format_date($format, $date) {
    $date_format = new DateTime($date);
    $new_date = $date_format->format($format);
    return $new_date;
}
