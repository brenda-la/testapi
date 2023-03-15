<?php

namespace App\Http\Controllers;

use App\Models\Produits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $produits= Produits::all();
        if(count($produits)<=0){
            
            return response(["message"=> "aucun produits disponible"],200); 

        }
        return response($produits,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $produitsValidation = $request -> validate(
            [
                "nom" => ["required", "string", ],
                "price" => ["required", "numeric"],
                "description" => ["required", "string","min:5"],
                "user_id" => ["required", "numeric"],
            ]
            );
            $produits= produits::create(
                
                [
                
                "nom" =>$produitsValidation["nom"] ,
                "price" => $produitsValidation["price"],
                "description" => $produitsValidation["description"],
                "user_id" => $produitsValidation["user_id"],
                ]
                );
                return response(["Message"=>"produit ajoute"],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Produits  $produits
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
        $produit = DB::table("Produits")
    
        ->join("users","produits.user_id","=","users.id")
        ->select("produits.*" , "users.name" ,"users.email" )
        ->where("produits.id", "=" ,$id)
        ->get()
        ->first();
        return $produit;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produits  $produits
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $produitsValidation = $request -> validate(
            [
                "nom" =>[ "string", ],
                "price" => [ "numeric"],
                "description" => [ "string","min:5"],
                "user_id" => ["required", "numeric"],
            ]
            ); 
            $produit= Produits::find($id) ;

            if(!$produit){
            
                return response(["message"=> "aucun produits trouve avec cet identifiant"],404); 
    
            }
            if($produit-> user_id != $produitsValidation["user_id"]){
                return response(["message"=> "action non authorisee"],403); 
            }

            $produit->update($produitsValidation);
            return response(["message"=> "le produit a ete mis a jour"],201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Produits  $produits
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request, $id)
    {
        $produitsValidation = $request -> validate(
            [
                "user_id" => ["required", "numeric"],
            ]
            );
        $produit= Produits::find($id) ;
        if(!$produit){
            
            return response(["message"=> "aucun produits trouve avec cet identifiant"],404); 

        }
        if($produit-> user_id != $produitsValidation["user_id"]){
            return response(["message"=> "action non authorisee"],403); 
        }

        $value = Produits::destroy($id);
        // if(boolval($value)== false){

        //    return response(["message"=> "aucun produits trouve avec cet identifiant"],404); 

        // }
         return response(["message"=> "produit suprime"],200); 
        
    }
}
