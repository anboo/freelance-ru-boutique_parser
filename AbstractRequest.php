<?php

/**
 * Created by PhpStorm.
 * User: anboo
 * Date: 27.02.16
 * Time: 6:55
 */
class AbstractRequest
{
    private $cookie;
    private $proxy;

    /**
     * @return mixed
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * @param mixed $cookie
     */
    public function setCookie($cookie)
    {
        $this->cookie = $cookie;
    }

    /**
     * @return mixed
     */
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * @param mixed $proxy
     */
    public function setProxy($proxy)
    {
        $this->proxy = $proxy;
    }

    private function baseConfigureResource($ch_init, array $headers = [])
    {
        curl_setopt ($ch_init, CURLOPT_COOKIEFILE, $this->cookie); // Сюда будем записывать cookies, файл в той же папке, что и сам скрипт
        curl_setopt ($ch_init, CURLOPT_COOKIEJAR, $this->cookie);
        curl_setopt ($ch_init, CURLINFO_HEADER_OUT, 1);
        curl_setopt ($ch_init, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt ($ch_init, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch_init, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch_init, CURLOPT_SSL_VERIFYPEER, true);

        if($this->proxy) {
            curl_setopt($ch_init, CURLOPT_PROXY, $this->proxy);
        }

        if($headers) {
            curl_setopt($ch_init, CURLOPT_HTTPHEADER, $headers);
        }

        curl_setopt($ch_init, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36");

        return $ch_init;
    }

    private function configureURL($url, array $params) {
        if($params) {
            $delimiter = preg_match('/\?/', $url) ? '&' : '?';
            $url = $url . $delimiter . http_build_query($params);
        }

        return $url;
    }

    protected function execGET($url, array $params = [], array $headers = [])
    {
        $url = $this->configureURL($url, $params);

        $ch_init = curl_init($url);
        $ch = $this->baseConfigureResource($ch_init, $headers);

        return curl_exec($ch);
    }

    protected function execPOST($url, array $params = [], array $headers = [])
    {
        $ch_init = curl_init($url);
        $ch = $this->baseConfigureResource($ch_init);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge([
            'Upgrade-Insecure-Requests:1',
            'Connection:keep-alive',
            'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Encoding:gzip, deflate',
            'Accept-Language:en-US,en;q=0.8,ru;q=0.6',
            'Cache-Control:max-age=0',
            'Content-Type:application/x-www-form-urlencoded',
            'User-Agent:Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36'
        ], $headers));

        return curl_exec($ch);
    }
}