<?php

namespace Pargar\Includes\Processing\API;

/**
 * APIPrintPdf
 *
 * Handles configuration and communication with the external PDF generation API.
 *
 * This class provides methods for setting PDF layout options such as page size,
 * dimensions, scale, signature, and URL. It can send a request to generate a PDF
 * from a remote service based on these parameters.
 *
 * Usage:
 * - Set parameters like URL, signature, scale, and dimensions.
 * - Call printPDF() to generate the document.
 *
 * Useful for scenarios such as invoice or report generation in web applications.
 *
 * @author     CodeArt
 * @link       https://code-art.ir
 * @package    Pargar
 * @subpackage Core
 * @since      1.0.0
 */
class APIPrintPdf
{

    private string $pages_size = "A4";
    private int $width = 0;
    private int $height = 0;
    private string $url = "";
    private string $page_orientation = "";
    private string $signature = "";
    private bool $page_size_auto = false;
    private float $scale = 0.5;

    /**
     * Set the desired page size for the PDF document.
     *
     * @param string $page Page size (e.g., A4, A5, etc.)
     * @since 1.0.0
     */
    public function setPageSize(string $page = "A4")
    {
        $this->pages_size = $page;
    }

    /**
     * Assign the URL of the document to be printed.
     *
     * @param string $url Full URL of the target document
     * @since 1.0.0
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * Set a signature string to be appended to the URL.
     *
     * @param string $signature Signature value for authentication or tracking
     * @since 1.0.0
     */
    public function setSignature(string $signature)
    {
        $this->signature = $signature;
    }

    /**
     * Define the scale factor for PDF rendering.
     *
     * @param float $scale Decimal value between 0 and 1 for zoom level
     * @since 1.0.0
     */
    public function setScale(float $scale)
    {
        $this->scale = $scale;
    }

    /**
     * Set the custom width for the generated PDF.
     *
     * @param int $width Desired width in pixels or printable units
     * @since 1.0.0
     */
    public function setWidth(int $width)
    {
        $this->width = $width;
    }

    /**
     * Set the custom height for the generated PDF.
     *
     * @param int $height Desired height in pixels or printable units
     * @since 1.0.0
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * Set the page orientation for the generated PDF.
     *
     * This method assigns the orientation value to an internal property.
     * Valid options typically include 'portrait' for vertical layout or 'landscape' for horizontal layout.
     * The selected orientation will be sent to the external PDF generation API when generating the document.
     *
     * @param string $page_orientation Page orientation ('portrait' or 'landscape')
     * @since 1.0.0
     */
    public function setPageOrientation($page_orientation)
    {
        $this->page_orientation = $page_orientation;
    }

    /**
     * Enable automatic page size adjustment based on content.
     *
     * @param bool $is If true, enables auto-sizing of the PDF page
     * @since 1.0.0
     */
    public function setPageSizeAuto(bool $is)
    {
        if ($is) {
            $this->page_size_auto = true;
        }
    }

    /**
     * Generate a new URL by appending the signature as a query parameter.
     *
     * @return string Modified URL with a signature parameter included.
     * @since 1.0.0
     */
    private function generateUrl()
    {
        $new_params = array(
            'signature' => $this->signature,
        );
        return add_query_arg($new_params, $this->url);
    }

    /**
     * Sends a POST request to an external PDF generation API
     * using the configured parameters like URL, page size, scale, dimensions, and signature.
     *
     * @return array|false Returns the API response body as an associative array if successful,
     *                     or false in case of an error or invalid status.
     * @since 1.0.0
     */
    public function printPDF()
    {
        $url = $this->generateUrl();
        if (is_multisite()) {
            $url = $this->edieUrlInMulti( $url );
        }

        $response = wp_remote_post('https://api.cvcrafter.ir/service/printpdf', array(
            'headers' => array(
                'Authorization' => 'Bearer 1Q3f4Vf6b09JRe0Q1vUaPblK',
            ),
            'body' => array(
                'token_service' => 'b9nvbWGW3P5HLo9',
                'purl' => $url,
                'pageSize' => $this->pages_size,
                'scale' => $this->scale,
                'width' => $this->width,
                'height' => $this->height,
                'layout' => $this->page_orientation,
                'page_size_auto' => $this->page_size_auto,
            ),
            'timeout' => 30
        ));

        if (is_wp_error($response)) {
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $body = json_decode($body, true);
        if (isset($body['status']) && $body['status'] == '0') {
            return false;
        }
        return $body;
    }

    private function edieUrlInMulti($url)
    {
        $current_blog_id = get_current_blog_id();
        $blog_details = get_blog_details( array( 'blog_id' => $current_blog_id ) );
        $path = $blog_details->path;

        $parsed = parse_url($url);

        $scheme   = $parsed['scheme'] ?? 'https';
        $host     = $parsed['host'] ?? '';
        $path     = $parsed['path'] ?? '';
        $query    = isset($parsed['query']) ? '?' . $parsed['query'] : '';
        $fragment = isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '';

        $segments = explode('/', trim($path, '/'));
        $filtered = [];
        $last = null;

        foreach ($segments as $segment) {
            if ($segment !== $last) {
                $filtered[] = $segment;
                $last = $segment;
            }
        }

        $clean_path = '/' . implode('/', $filtered) . '/';

        return $scheme . '://' . $host . $clean_path . $query . $fragment;
    }
}