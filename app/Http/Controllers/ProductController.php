<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    public function store(Request $request)
    {
        //importing necessary inputs
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'image_link' => 'required|string|url|max:100',
            'category_id' => 'required|numeric|min:1',
            'contact_info' => 'required|string',
            'quantitiy' => 'required|numeric',
            'initial_price' => 'required|numeric',
            'first_evaluation_date' => 'required|date',
            'second_evaluation_date' => 'required|date',
            'expiration_date' => 'required|date',
            'first_discount_percentage' => 'required|numeric|between:1,100',
            'second_discount_percentage' => 'required|numeric|between:1,100',
            'third_discount_percentage' => 'required|numeric|between:1,100',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        Product::create(array_merge($validator->validated(),));
    //     //standard json import/decode
    //     $filePath = 'C:\xampp\htdocs\Products_list.json';
    //     $fileContent = file_get_contents($filePath);
    //     $jsonContent = json_decode($fileContent, true);
    //     //entering input values into an array
    //     $payload = [
    //         'name' => $name,
    //         'img_url' => $imageUrl,
    //         'initial_price' => $initPrice,
    //         'brand' => $contactLink
    //     ];
    //     // if json file is empty/doesn't exist then put the array directly into the json file (and create it if necessary)
    //     if (!$jsonContent || !is_array($jsonContent)) {
    //         $content = [$payload];
    //         file_put_contents($filePath, json_encode($content));
    //     } else {
    //         //else add the new values into the file (without erasing previous values)
    //         $jsonContent[] = $payload;
    //         file_put_contents($filePath, json_encode($jsonContent));
    //     }
    //     return response()->json(['message' => 'Product has been added successfully', 'data' => $payload]);
    // }

        }
    public function destroy($productId)
    {
        //standard json import/decode
        $filePath = 'C:\xampp\htdocs\Products_list.json';
        $fileContent = file_get_contents($filePath);
        $jsonContent = json_decode($fileContent, true);

        //Checking that the requested ID is valid
        if ($productId <= 0 || $productId > count($jsonContent))
            return response()->json(['message' => 'Invalid ID'], 400);
        //Destroying the product values
        unset($jsonContent[$productId - 1]);
        //recreating the json file after deleting the product
        file_put_contents($filePath, json_encode(array_values($jsonContent)));
        return response()->json(['message' => 'Product has been deleted successfully']);
    }

    public function index()
    {
        //standard json import/decode
        $filePath = 'C:\xampp\htdocs\Products_list.json';
        $fileContent = file_get_contents($filePath);
        $jsonContent = json_decode($fileContent, true);
        return response()->json([
            'message' => 'Data retrieved successfully!',
            'data' => $jsonContent //Printing out all the product values
        ]);
    }
    
    public function update($productId, Request $request)
    {
        $filePath = 'C:\xampp\htdocs\Products_list.json';
        $fileContent = file_get_contents($filePath);
        $jsonContent = json_decode($fileContent, true);
        //Checking that the requested ID is valid
        if ($productId <= 0 || $productId > count($jsonContent))
            return response()->json(['message' => 'Invalid ID'], 400);
        //importing necessary inputs
        $name = $request->input('name');
        //Checking validty of inputs
        if (!$name)
            return response()->json(['message' => 'invalid payload, name field is required', 'data' => null], 400);
        //Updating the product name
        $jsonContent[$productId - 1]['name']=$name;
        //Reintroducing the updated product into the json file
        file_put_contents($filePath, json_encode(array_values($jsonContent)));
        return response()->json([ 
        'message' => 'Data updated successfully!',
        'data' => $jsonContent[$productId - 1]]); //Printing out the new product data
        }
    public function show($productId)
    {

    }
}
