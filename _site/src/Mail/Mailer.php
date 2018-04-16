<?php
namespace Brunelencantado\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Brunelencantado\Database\DbInterface;

/**
 * Mailer class
 * 
 * Uses PHPMailer and wraps it into an easy to use interface.
 * 
 * @author Daniel Beard <daniel@creativos.be>
 *  
 */
class Mailer
{

    protected $debug = false;

    protected $mailer;
    protected $db;
    protected $config;
    protected $template;
    protected $viewData;
    protected $message;
    protected $dataTable;
    protected $extraData;
    protected $subject = 'Contact';
    protected $body;
    protected $logo;
    protected $sent;

    /**
     * @brief Constructor
     *
     * @param PHPMailer $mailer
     * @param DbInterface $db
     * @param Array $config [ host, port, user, password ]
     * @return Void
     */
    public function __construct(PHPMailer $mailer, DbInterface $db, array $config)
    {

        $this->mailer = $mailer;
        $this->db = $db;
        $this->config = $config;

        $this->logo = dirname(__FILE__) . '/../../images/logo-mail.png';

        $this->setupMailer();
        $this->withTemplate('default');

    }

    /**
     * @brief Static Mailer Factory
     *
     * @param DbInterface $db
     * @param Array $emailConfig [ host, port, user, password ]
     * @return Mailer
     */
    public static function createMailer(DbInterface $db, array $emailConfig)
    {

        $phpMailer = new PHPMailer(true);

        return new Mailer($phpMailer, $db, $emailConfig);

    }

    /**
     * @brief Collects current subject and message, recipient and sender, and sends the email
     *
     * @return Mailer
     */
    public function send()
    {

        $this->mailer->Subject = $this->subject;
        $this->mailer->Body = $this->getMessageBody();

        // Send the mail!
        try {
            
            $this->mailer->send();
            $this->sent = true;

        } catch (Exception $e) {

            echo 'Message not sent.';
            echo 'Mailer error: ' . $this->mailer->ErrorInfo;

        }

        return $this;
        
    }

    /**
     * @brief Adds address to the PHPMailer object, clearing current address
     *
     * @param String $email
     * @param String $name
     * @return Mailer
     */
    public function to($email, $name = null)
    {

        $this->mailer->ClearAllRecipients();
        $this->mailer->addAddress($email, $name);

        return $this;

    }

    /**
     * @brief Adds CC address to the PHPMailer object
     *
     * @param String $email
     * @param String $name
     * @return Mailer
     */
    public function addCC($email, $name = null)
    {

        $this->mailer->addCC($email, $name);

        return $this;

    }

    /**
     * @brief Adds BCC address to the PHPMailer object
     *
     * @param String $email
     * @param String $name
     * @return Mailer
     */
    public function addBCC($email, $name = null)
    {

        $this->mailer->addBCC($email, $name);

        return $this;

    }

    /**
     * @brief Sets from address & name. Defaults are from _config table
     *
     * @param String $email
     * @param String $name
     * @return Mailer
     */
    public function from($email, $name = null)
    {

        $this->mailer->setFrom($email, $name);

        return $this;

    }

    /**
     * @brief Sets email subject
     *
     * @param String $subject
     * @return Mailer
     */
    public function subject($subject)
    {

        $this->subject = $subject;

        return $this;

    }

    /**
     * @brief Sets email message
     *
     * @param String $message
     * @return Mailer
     */
    public function message($message)
    {

        $this->message = $message;

        return $this;

    }

    /**
     * @brief Adds view data
     *
     * @param Array $viewData
     * @return Mailer
     */
    public function with(array $viewData = [])
    {

        $this->viewData = $viewData;
        
        return $this;

    }

    /**
     * @brief Adds content form database, allowing for %%VARIABLE%% substitution
     *
     * @param String $key clave field from _emails table
     * @param Array $data [ '%%VARIABLE%%' => $substitute]
     * @param Boolean $language
     * @return Mailer
     */
    public function addContentByKey($key, $data = [], $language = null)
    {

        $lang = ($language) ? $language : LANGUAGE;

        $query = "SELECT asunto_{$lang} AS asunto, texto_{$lang} AS texto FROM ".XNAME."_emails WHERE clave = '{$key}'";
        $sql = $this->db->record($query);

        $asunto = $sql['asunto'];
        $texto = $sql['texto'];

        if (!empty($data)) {

            foreach ($data as $k => $v) {

                $asunto = str_replace($k, $v, $asunto);
                $texto = str_replace($k, $v, $texto);

            }

        }

        $this->subject = $asunto;
        $this->message = $texto;

        return $this;

    }

    /**
     * @brief Converts data to an HTML table to add to the email body, after the message. Some keys can be ignored via the $ignores array.
     *
     * @param Array $data
     * @param Array $ignores
     * @return Mailer
     */
    public function addDataTable(array $data, array $ignores = [])
    {

        // Create table from POST data, minus $ignores fields
        $output = '<table>';

        foreach ($data as $k => $v) {

            if (in_array($k, $ignores)) continue;
            if ($v == '') continue;
            if ($k == 'link') $v = '<a href="' . $v . '">' . $v . '</a>';

            $output .= '<tr><td><strong>' . trad($k) . ':</strong>&nbsp;</td><td>' . $v . '</td></tr>';

        }

        $output .= '</table>';

        $this->dataTable = $output;

        return $this;

    }

    /**
     * @brief Adds extra data to email template, after main message and datatable
     *
     * @param String $extraData
     * @return Void
     */
    public function addExtraData($extraData)
    {
       
        $this->extraData = $extraData;

        return $this;

    }

    /**
     * @brief Sets a file to be attached
     *
     * @param String $file File path
     * @return Mailer
     */
    public function attach($file)
    {

        $this->mailer->addAttachment($file); 

        return $this;

    }

    /**
     * @brief Adds multiple attachments
     *
     * @param Array $attachments
     * @return Mailer
     */
    public function addAttachments(array $attachments)
    {

        foreach ($attachments as $attachment){

            $this->attach($attachment);

        }

        return $this;

    }

    /**
     * @brief Adds embedded images
     *
     * @param Array $images
     * @return Mailer
     */
    public function addEmbeddedImages(array $images)
    {
        foreach ($images as $index => $image){

            $this->mailer->AddStringEmbeddedImage(file_get_contents($image), 'embedded' . $index, 'attachment', 'base64', 'image/jpeg'); 

        }

        return $this;

    }

    /**
     * @brief Sets template to be used. Templates located in src/Mail/Templates. default.template.php is default.
     *
     * @param String $template Template name
     * @return Mailer
     */
    public function withTemplate($template)
    {

        $template = dirname(__FILE__) . '/Templates/' . $template . '.template.php';

        if (file_exists($template)) {

            $this->template = $template;

            return $this;

        }

        throw new Exception('Mail template does not exist.');

    }

    /**
     * @brief Sets debug mode
     *
     * @param Integer $debug Can be 1, 2 or 3
     * @return Mailer
     */
    public function debug($debug)
    {

        $this->debug = $debug;

        return $this;

    }

    /**
     * @brief Gets dataTable
     *
     * @return String
     */
    public function getDataTable()
    {

        return $this->dataTable;

    }

    /**
     * @brief Sets up & configures PHPMailer object.
     *
     * @return Void
     */
    protected function setupMailer()
    {

        // Make variables local
        $mailer = $this->mailer;
        $config = $this->config;

        // Basic settings
        $mailer->SMTPDebug     = $this->debug;
        $mailer->SMTPAuth      = true;
        $mailer->SMTPSecure    = 'tls';
        $mailer->CharSet       = 'UTF-8';
        $mailer->WordWrap      = 50;
        $mailer->IsSMTP(true);
        $mailer->isHTML(true);

        // Server settings
        $mailer->Host       = $config['host'];
        $mailer->Username   = $config['user'];
        $mailer->Password   = $config['pass'];
        $mailer->Port       = $config['port'];
      
        // From settings
        $mailer->setFrom($config['default_from_address'], $config['default_from_name']);

        // Content
        $mailer->Subject = $this->subject;
        $mailer->Body = $this->body;

        // Attach logo for embedding
        $mailer->AddEmbeddedImage($this->logo, 'mailheader', 'attachment', 'base64', 'image/png'); 

        $this->mailer = $mailer;

    }

    /**
     * @brief Interprets template with the data
     *
     * @return String
     */
    protected function getMessageBody()
    {

        ob_start(); 

            $header = (file_exists($this->logo)) ? '<img src="cid:mailheader" />' : '<h1>' . webConfig('nombre') . '</h1>'; 
            $message = $this->message;
            $dataTable = $this->dataTable;
            $extraData = $this->extraData;
            
            require $this->template;

            $emailBody = ob_get_contents();

        ob_end_clean();

        return $emailBody;

    }

}