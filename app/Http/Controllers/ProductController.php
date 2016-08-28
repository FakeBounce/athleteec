<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use App\Brand;
use App\Carac;
use App\Carac_val;
use App\Picture;
use App\UsersEquipsSports;
use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addAjax(Request $request)
    {
        if(\Request::ajax()){

            $product = new Product();

            $data = $request->all();

            $rules = [
                'productname' => 'required',
                'description' => 'required',
                'category' => 'required',
                'brand' => 'required',
                'productpicture' => 'mimes:jpeg,png,jpg'
            ];

            $messages = [
                'productname.required'    => 'Nom du produit requis',
                'description.required'    => 'Description requise',
                'category.required'    => 'Catégorie requise',
                'brand.required'    => 'Marque requise',
                'productpicture.mimes'      => 'Le format de l\'image n\'est pas pris en charge (jpeg,png,jpg)'
            ];

            $validator = Validator::make($data,$rules,$messages);

             if ($validator->fails()) {
                return array(
                    'errors' => $validator
                );
            }

            $category = Category::where('name',$data['category'])->get()->first();
            
            if(empty($category))
            {
                $category = new Category();
                $category->name = htmlspecialchars(trim($data['category']));
                $category->save();
            }

            $brand = Brand::where('name',$data['brand'])->get()->first();
            
            if(empty($brand))
            {
                $brand = new Brand();
                $brand->name = htmlspecialchars(trim($data['brand']));
                $brand->save();
            }

            $product->name = htmlspecialchars($data['productname']);
            $product->description = htmlspecialchars($data['description']);
            $product->category_id = $category->id;
            $product->brand_id = $brand->id;
            $product->price= $data['price'];
            $product->sell=0;
            $product->url = htmlspecialchars($data['url']);


            if ($request->hasFile('productpicture')) {
                $guid = sha1(time());
                $imageName = $guid . "." . $request->file('productpicture')->getClientOriginalExtension();;

                $request->file('productpicture')->move(
                    public_path() . '/images/products', $imageName
                );

                $imageName .= '/images/products/'.$imageName;

                $product->picture = $imageName;
            }
            else{
                $product->picture = "/default_picture/default-equipement.jpg";
                $imageName = "default_picture/default-equipement.jpg";
            }

            $product->save();
            
            $sportid = 1;
            $userEquipsSports = new UsersEquipsSports();
            $userEquipsSports->user_id = Auth::user()->id;
            $userEquipsSports->product_id= $product->id;
            $userEquipsSports->sport_id= $sportid;

            $userEquipsSports->save();
            
            if(!empty($data['new_carac_name']))
            {
                $caracs_id = [];
                $p = 0;
                $new_caracs = $request->input('new_carac_name');
                foreach($new_caracs as $new_carac)
                {
                    $carac = new Carac();
                    $carac->name = $new_carac;
                    $carac->category_id = $category->id;

                    $carac->save();
                    $caracs_id[$p] = $carac->id;
                    $p++;
                }
            }
            
            if(!empty($data['new_carac_val']))
            {
                $new_caracs_val = $request->input('new_carac_val');
                $p = 0;
                foreach($new_caracs_val as $new_val)
                {
                    $new_val = new Carac_val();
                    $new_val->value = $new_carac;
                    $new_val->carac_id = $caracs_id[$p];
                    $new_val->product_id = $product->id;

                    $new_val->save();
                    $p++;
                }
            }
            
            return \Response::json(array(
                'success' => true,
                'productname' =>  $data['productname'],
                'description' => $data['description'],
                'price' => $data['price'],
                'url' => $data['url'],
                'productId' => $product->id,
                'picture' => $imageName,
                'test' => $data['new_carac_val'],
                'test2' => $data['new_carac_name']
            ));
        }
    }

    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
            
        $categories = DB::table('categories')->get();
        $brands = DB::table('brands')->get();
        $caracs = DB::table('caracs')->get();
        $carac_vals = DB::table('carac_vals')->get();
        return view('front.product.show',['product' => $product,'caracs' =>$caracs,'carac_vals' =>$carac_vals, 'categories'=>$categories,'brands'=>$brands]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        
        if($product->user_id != Auth::user()->id)
        {
            
            $categories = DB::table('categories')->get();
            $brands = DB::table('brands')->get();
            $caracs = DB::table('caracs')->get();
            $carac_vals = DB::table('carac_vals')->get();
            return view('front.product.edit',['product' => $product,'caracs' =>$caracs,'carac_vals' =>$carac_vals, 'categories'=>$categories,'brands'=>$brands]);
        }
        else
        {
            return view('front.product.edit',['product' => $product]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        
        if($product->user_id == Auth::user()->id)
        {
            $data = $request->all();

            $rules = [
                'description' => 'required',
                'category' => 'required',
                'brand' => 'required',
                'productpicture' => 'mimes:jpeg,png,jpg'
            ];

            $messages = [
                'description.required'    => 'Description requise',
                'category.required'    => 'Catégorie requise',
                'brand.required'    => 'Marque requise',
                'productpicture.mimes'      => 'Le format de l\'image n\'est pas pris en charge (jpeg,png,jpg)'
            ];

            $validator = Validator::make($data,$rules,$messages);

             if ($validator->fails()) {
                return array(
                    'errors' => $validator
                );
            }
            
            
            $category = Category::where('name',$data['category'])->get()->first();
            
            if(empty($category))
            {
                $category->name = htmlspecialchars(trim($data['category']));
                $category->save();
            }

            $brand = Brand::where('name',$data['brand'])->get()->first();
            
            if(empty($brand))
            {
                $brand = new Brand();
                $brand->name = htmlspecialchars(trim($data['brand']));
                $brand->save();
            }

            $product->description = htmlspecialchars($data['description']);
            $product->category_id = $category->id;
            $product->brand_id = $brand->id;
            $product->price= $data['price'];
            $product->url = htmlspecialchars($data['url']);


            if ($request->hasFile('productpicture')) {
                $guid = sha1(time());
                $imageName = $guid . "." . $request->file('productpicture')->getClientOriginalExtension();;

                $request->file('productpicture')->move(
                    public_path() . '/images/products', $imageName
                );

                $imageName .= '/images/products/'.$imageName;

                $product->picture = $imageName;
            }
            else{
                $product->picture = "/default_picture/default-equipement.jpg";
                $imageName = "default_picture/default-equipement.jpg";
            }

            $product->save();
            
            if(!empty($data['new_carac_name']))
            {
                $caracs_id = [];
                $p = 0;
                $new_caracs = $request->input('new_carac_name');
                foreach($new_caracs as $new_carac)
                {
                    $carac = new Carac();
                    $carac->name = $new_carac;
                    $carac->category_id = $category->id;

                    $carac->save();
                    $caracs_id[$p] = $carac->id;
                    $p++;
                }
            }
            
            if(!empty($data['new_carac_val']))
            {
                $new_caracs_val = $request->input('new_carac_val');
                $p = 0;
                foreach($new_caracs_val as $new_val)
                {
                    $new_val = new Carac_val();
                    $new_val->value = $new_carac;
                    $new_val->carac_id = $caracs_id[$p];
                    $new_val->product_id = $product->id;

                    $new_val->save();
                    $p++;
                }
            }
        }
        return Redirect::route('product.show', ['product' => $product])->with('message', 'Modification effectué avec succès');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $carac_vals = Carac_val::where('product_id',$product->id)
            ->get();
        if(!empty($carac_vals))
        {
            foreach($carac_vals as $carac_val)
            {
                $carac_val->delete();
            }
        }
        
        $equip_sports = UsersEquipsSports::where('product_id',$product->id)
            ->where('user_id',Auth::user()->id)
            ->get()
            ->first();
        if(!empty($equip_sports))
        {
            $equip_sports->delete();
        }
        $data = DB::table('products')->where('id',$product->id)->delete();
        
        return Redirect::to('/');
    }
    
    
    public function comparator(Product $product)
    {
        $produits = session()->get('produits');
        if(!empty($produits[0]))
        {
            if($produits[0]['category_id'] == $product->category_id)
            {
                array_push($produits,$product);
                session()->set('produits', $produits);
            return Redirect::to('/product/1/compare')->with('message', 'Les produits doivent être de la même catégorie.');
            }
            
        }
        return Redirect::to('/product/1/compare');
    }
    
    public function compare(Product $product)
    {
            
        $categories = DB::table('categories')->get();
        $brands = DB::table('brands')->get();
        $caracs = DB::table('caracs')->get();
        $carac_vals = DB::table('carac_vals')->get();
        
        return view('front.product.compare',['product' => $product,'caracs' =>$caracs,'carac_vals' =>$carac_vals, 'categories'=>$categories,'brands'=>$brands]);
    }
    
    public function flush(Product $product)
    {
        $test = [];
        session()->set('produits', $test);
        return Redirect::to('/');
    }
}
