<?php
require_once('AbstractRequest.php');

/**
 * Created by PhpStorm.
 * User: anboo
 * Date: 27.02.16
 * Time: 6:41
 */
class FreelanceRequest extends AbstractRequest
{


    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;


    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }


    public function loginFreelanceRu() {
        //Initial request for adding base cookie
        $this->execGET('https://freelance.ru/login/');

        //Form data for send (simulate form login request)
        $data = [
            'login'      => $this->getLogin(),
            'passwd'     => $this->getPassword(),
            'check_ip'   => 'on',
            'submit'     => 'Вход',
            'auth'       => 'auth',
            'return_url' => '/login/'
        ];

        $headers = [
            'Host:freelance.ru',
            'Origin:https://freelance.ru',
            'Referer:https://freelance.ru/login/',
        ];

        return $this->execPOST('https://freelance.ru/login/', $data, $headers);
    }

    public function loginFreelanceBoutique() {
        //initial request for setting base cookie
        $this->execGET('https://freelance.boutique/user/auth/login');
        //initial request for auth from freelance.ru
        $this->execGET('https://freelance.boutique/user/auth/freelanceru');
    }

    public function showPage($url, $referer = null) {
        return $this->execGET($url, [], $referer ? ['Referer:'.$referer] : []);
    }

    public function createProjectFor($login, array $formData) {

        $formData = http_build_query($formData);

        $url = 'https://freelance.boutique/ws/standalone/create/exe/'.$login;
        $this->execPOST($url, $formData);

        /*
         * name:написание текста для сайта topms.ru
        cost:4000
        payment[]:bank
        payment[]:emoney
        payment[]:cash
        interval:5
        descr:Нужно написать текст для сайта, подробнее в скайпе!
        must:Обязательных требований нет
        sbtn:Предложить проект
         */

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Upgrade-Insecure-Requests:1',
            'Host:freelance.boutique',
            'Origin:https://freelance.boutique',
            'Referer:https://freelance.boutique/ws/standalone/create/exe/danieldebil',
            'Connection:keep-alive',
            'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Encoding:gzip, deflate',
            'Accept-Language:en-US,en;q=0.8,ru;q=0.6',
            'Cache-Control:max-age=0',
            'Content-Type:application/x-www-form-urlencoded',
            'User-Agent:Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36'
        ]);
    }

}