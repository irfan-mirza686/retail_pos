<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Models\Product;
use App\Models\Unit;
use Exception;
use GuzzleHttp\Client;

class ProductController extends Controller
{
    /*====================================*/
    public function authenticateRole($module_page)
    {
        $permissionCheck = checkRolePermission($module_page);
        if ($permissionCheck->access == 0) {

            return redirect()->to('/dashboard')->send()->with('error', 'You have no permission!');
        }
    }
    /*===================================================*/
    public function index()
    {
        // $this->authenticateRole($module_page = 'product');
        Session::put('page', 'viewProducts');
        $products = Product::with(['users', 'units'])->get();
        $user_info = session('user_info');
        return view('products.view', compact('products','user_info'));
    }
    /*==============================================================*/
    public function addProduct(Request $request)
    {
        // $this->authenticateRole($module_page='product');
        if ($request->isMethod('post')) {
            $data = $request->all();
            $user_info = session('user_info');
            // echo "<pre>"; print_r($user_info['token']); exit;
            // try {
            if (is_numeric($data['unit_id']) && ($data['unit_id'] > 0)) {
                if ($data['product_type'] == 'gs1') {
                    try {
                        $client = new Client();
                        $response = $client->request('POST', 'https://gs1ksa.org:3093/api/products', [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $user_info['token'], // Include the token in the 'Authorization' header
                            ],
                            'form_params' => [
                                'user_id' => $user_info['memberData']['id'],
                                // 'gcpGLNID' => $user_info['memberData']['gcpGLNID'],
                                'productnameenglish' => $data['name'],
                                'productnamearabic' => $data['name'],
                                'BrandName' => $data['name'],
                                'ProductType' => 'Electronics',
                                'Origin' => 'USA',
                                'PackagingType' => 'Box',
                                'unit' => $data['unit_id'],
                                'size' => $data['size'],
                                'gpc' => 'GPC123',
                                'gpc_code' => '10000027',
                                'countrySale' => 'SAU',
                                'HSCODES' => '1234.56.78',
                                'HsDescription' => 'Sample HS Description',
                                'gcp_type' => '1',
                                'prod_lang' => 'en',
                                'details_page' => 'en description',
                                'details_page_ar' => 'ar description'
                            ]
                        ]);

                        return redirect()->back()->with('success', 'Product successfully Added!');
                    } catch (RequestException $e) {
                        if ($e->hasResponse()) {
                            // Extract the error message from the response body
                            $responseBody = $e->getResponse()->getBody()->getContents();
                            $responseData = json_decode($responseBody, true);
                            // echo "<pre>"; print_r($responseData['error']); exit;
                            $errorMessage = isset($responseData['error']) ? $responseData['error'] : 'An unexpected error occurred.';
                        } else {
                            // If the response is not available, use a default error message
                            $errorMessage = 'An unexpected error occurred.';
                        }

                        // You can log the error message
                        \Log::error('Guzzle HTTP request failed: ' . $errorMessage);

                        // Return an error response with the extracted error message
                        return redirect()->back()->with('success', $errorMessage);
                    } catch (\Throwable $th) {
                        \Log::error('An unexpected error occurred: ' . $e->getMessage());

                        // Return an error response
                        return redirect()->back()->with('success', 'An unexpected error occurred. Please try again later.');
                    }

                } else {
                    $product = new Product;
                    $product->name = $data['name'];
                    $product->unit_id = $data['unit_id'];
                    $product->cost = $data['cost'];
                    $product->selling_price = $data['selling_price'];
                    $product->quantity = isset($data['quantity']) ? $data['quantity'] : 0;
                    $product->product_code = $data['product_code'];
                    $product->user_id = Auth::user()->id ?? 0;
                    $product->memberID = $user_info['memberData']['id'];
                    $product->save();
                }

                return redirect('/products')->with('success', 'Product successfully Added!');
            } else {
                return redirect()->back()->with('error', 'Unit cannot be nulled!');
            }


            // } catch (\Throwable $th) {
            //     Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
            //     return redirect()->back()->with($th->getMessage());
            // }
        } else {
            Session::put('page', 'addProduct');
            $user_info = session('user_info');
            $units = Unit::get();
            $title = "Add Product";
            return view('products.add_prodcut', compact('units', 'title', 'user_info'));
        }
    }
    /*==============================================================*/
    public function editProduct(Request $request, $id = null)
    {
        $this->authenticateRole($module_page = 'product');
        $editProduct = Product::find($id);
        $data = $request->all();
        if ($request->isMethod('post')) {
            try {
                $editProduct->name = $data['name'];
                $editProduct->unit_id = $data['unit_id'];
                $editProduct->cost = $data['cost'];
                $editProduct->selling_price = $data['selling_price'];
                $editProduct->quantity = $data['quantity'];
                $editProduct->user_id = Auth::user()->id;
                $editProduct->product_code = $data['product_code'];
                $editProduct->save();
                return redirect('/products')->with('success', 'Product successfully Updated!');
            } catch (Exception $e) {
                Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
                return redirect()->back()->with($e->getMessage());
            }
        } else {
            $units = Unit::get();
            $title = "Update Product";
            return view('products.add_prodcut', compact('units', 'editProduct', 'title'));
        }
    }

    /*==============================================================*/
    public function deleteProduct($id = null)
    {

    }
    /*==============================================================*/
}
