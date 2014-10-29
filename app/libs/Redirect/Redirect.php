<?php namespace libs\Redirect;

use \libs\Config\Config;
use \libs\Request\Request;
use \libs\Response\Response;
use \libs\Language\Language;

class Redirect {

	private $config;
	private $request;
	private $response;
	private $lang;

	public function __construct(Config $config, Request $request, Response $response, Language $lang){
		$this->config = $config;
		$this->request = $request;
		$this->response = $response;
		$this->lang = $lang;
	}

    /**
     * Redirects the current request to another URL.
     *
     * @param string $url URL
     * @param int $code HTTP status code
     */
    public function go($url, $code = 303) {
        $base = $this->config->get('app.base_url');

        if ($base === null) {
            $base = $this->request->base;
        }

        // Append base url to redirect url
        if ($base != '/' && strpos($url, '://') === false) {
            $url = preg_replace('#/+#', '/', $base.'/'.$url);
        }

        $this->response
        	->status($code)
            ->header('Location', $url)
            ->write($url)
            ->send();
    }

    public function to($url) {
    	return $this->go($this->lang->link($url));
    }


}