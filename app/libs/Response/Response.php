<?php namespace libs\Response;

class Response extends \flight\net\Response {

    /**
     * Sends a JSON response.
     *
     * @param mixed $data JSON data
     * @param int $code HTTP status code
     * @param bool $encode Whether to perform JSON encoding
     */
    public function json($data, $code = 200, $encode = true) {
        $json = ($encode) ? json_encode($data) : $data;

        $this->status($code)
            ->header('Content-Type', 'application/json')
            ->write($json)
            ->send();
    }

    /**
     * Stops processing and returns a given response.
     *
     * @param int $code HTTP status code
     * @param string $message Response message
     */
    public function halt($code = 200, $message = '') {
        $this->status($code)
            ->write($message)
            ->send();
    }

    /**
     * Sends an HTTP 500 response for any errors.
     *
     * @param \Exception Thrown exception
     */
    public function error(\Exception $e) {
        $msg = sprintf('<h1>500 Internal Server Error</h1>'.
            '<h3>%s (%s)</h3>'.
            '<pre>%s</pre>',
            $e->getMessage(),
            $e->getCode(),
            $e->getTraceAsString()
        );

        try {
            $this->status(500)
                ->write($msg)
                ->send();
        }
        catch (\Exception $ex) {
            exit($msg);
        }
    }

    /**
     * Sends an HTTP 404 response when a URL is not found.
     */
    public function notFound() {
        $this->status(404)
            ->write(
                '<h1>404 Not Found</h1>'.
                '<h3>The page you have requested could not be found.</h3>'.
                str_repeat(' ', 512)
            )
            ->send();
    }

}