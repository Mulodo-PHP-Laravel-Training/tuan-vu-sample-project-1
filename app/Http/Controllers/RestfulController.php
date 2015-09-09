<?php
namespace App\Http\Controllers;

use App\Exceptions\ValidationException;
use App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Http\Request;

class RestfulController extends Controller
{

    private $statusCode    = 200;
    private $format        = 'json';
    protected $inputRequest;
    protected $rules = [];
    protected $nameInputParams = [];

    /**
     * Get value of specific parameters
     *
     * @param Request $request
     *
     * @return array
     */
    public function getInput(Request $request)
    {
        $this->inputRequest = $request->only($this->nameInputParams);

        return $this->inputRequest;
    }

    /**
     * Validate the given request with the given rules.
     *
     * @param Request $request
     * @param array   $messages
     *
     * @throws \App\Exceptions\ValidationException
     */
    public function validator(array $messages = [])
    {
        $validator = Validator::make($this->inputRequest, $this->rules);

        $messages = ($messages) ? $messages : $validator->messages();

        if ($validator->fails()) {
            throw new ValidationException($messages);
        }
    }
    
    /**
     * Format API response in case successful
     *
     * @param  array $data
     *
     * @return array
     */
    public function formatApiSuccess($data = null)
    {
        return [
            'status' => 'success',
            'result' => $data,
        ];
    }

    /**
     * Response api in JSON/XML/Plaintext
     *
     * @param $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseApi($data)
    {
        switch ($this->format)
        {
            case 'json':
                return response()->json($data, $this->statusCode);
            case 'xml':
                return 'Does not build this function';
            default:
                break;
        }
    }
}