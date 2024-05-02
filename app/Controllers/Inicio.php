<?php namespace App\Controllers;

use App\Models\ProductosModel;
use App\Models\VentasModel;

class Inicio extends BaseController
{
	protected $productoModel;
	protected $ventasModel, $session;


	public function __construct(){
		$this->productoModel=new ProductosModel();
		$this->ventasModel=new VentasModel();
		$this->session=session();

	}

	public function index()
	{
		if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
		$total=$this->productoModel->totalProductos();
		$totalDia=$this->ventasModel->totalDia(date('Y-m-d'));
		$totalVentas=$this->ventasModel->totalVentas(date('Y-m-d'));
		$minimos=$this->productoModel->productosMinimo();

		$datos=['total'=>$total, 'totalDia'=>$totalDia, 'totalVentas'=>$totalVentas, 'minimos'=>$minimos];
		
		echo view('header');
		echo view('inicio', $datos);
		echo view('footer');
	}

	//--------------------------------------------------------------------

}
