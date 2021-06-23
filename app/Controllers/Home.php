<?php namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\VentaModel;

class Home extends BaseController
{
    private $contenedor = '<div class="as-producttile large-4 small-6 group-1">
    <div class="as-producttile-tilehero with-paddlenav with-paddlenav-onhover">
        <div class="as-dummy-container as-dummy-img">

            <img src="./assets/{img}" class="ir ir item-image as-producttile-image  " alt="" width="445" height="445">
        </div>
        <div class="images mini-gallery gal1 ">
            <ul class="clearfix as-producttile-nojs">
                <li class="as-searchtile-nojs">
                    <img src="./assets/{$img}>" class="ir relatedlink item-image as-producttile-image" alt="" width="445" height="445" data-scale-params-2="wid=890&amp;hei=890&amp;fmt=jpeg&amp;qlt=95&amp;op_usm=0.5,0.5&amp;.v=1502831144597">
                </li>
            </ul>

            <div class="as-isdesktop with-paddlenav with-paddlenav-onhover">
                <div class="clearfix image-list xs-no-js as-util-relatedlink relatedlink" data-relatedlink="2|Beats Studio3 Wireless Over‑Ear Headphones - Shadow Gray|MQUF2">
                    <div class="as-tilegallery-element as-image-selected">
                        <div class="">

                        </div>
                        <img src="./assets/{img}" class="ir ir item-image as-producttile-image" alt="" data-desc="Samsung galaxy" style="content:-webkit-image-set(url(https://md6.pricebaba.com/images/product/mobile/46638/{img}">
                    </div>

                </div>
            </div>
        </div>

    </div>
    <div class="as-producttile-info" style="min-height: 168px;">
        <div class="as-producttile-titlepricewraper" style="min-height: 128px;">
            <div class="as-producttile-title">
                <h3 class="as-producttile-name">
                    <p class="as-producttile-tilelink">
                        <span data-ase-truncate="2">{title}</span>
                    </p>

                </h3>
            </div>
            <div class="as-price-currentprice as-producttile-currentprice">
                ${price}
            </div>
        </div>
        <form action="detalle" method="post">
            <input type="hidden" name="img" value="./assets/{img}">
            <input type="hidden" name="title" value="{title}">
            <input type="hidden" name="price" value="{price}">
            <input type="hidden" name="unit" value="1">
            <input type="hidden" name="id_producto" value="{id_producto}">
            <button type="submit" class="mercadopago-button" formmethod="post">Comprar</button>
        </form>
    </div>
</div>';

    public function index()
	{
	    helper('form');
	    $modelo = new ProductoModel();
        $parser = \Config\Services::parser();

        $productos = $modelo->findAll();

        $datos['contenido'] = '';

        $formulario = form_open('detalle');
        $template = $this->contenedor;

        foreach ($productos as $producto) {
            $producto['formulario'] = $formulario;
            $datos['contenido'] .= $parser->setData($producto)->renderString($template);
        }

        echo view('header');
        echo view('inicio',$datos);
        echo view('footer');
	}

    public function detalle_telefono(){
        echo view('header');
        echo view('detalle');
        echo view('footer');
    }

    public function feedback(){

        $model = new VentaModel();
        $venta = $model->find($this->request->getGet('external_reference'));


        $data = [];
        $data['id_venta'] = $venta['id_venta'];
        $data['payment_id'] = $this->request->getGet('payment_id');
        $data['status'] = $this->request->getGet('status');

        $model->save($data);

        switch ($this->request->getGet('status')){
            case 'approved':
                return redirect()->to('aprobada/'.$venta['id_venta']);
                break;
            case 'pending':



                break;
            case 'rejected':
                break;
            default:
                break;
        }

    }

    public function result($id){
        $modelo = new VentaModel();
        $venta = $modelo->get_detalle_venta($id)[0];

        $uri = $request->uri;



        echo view('header');
        echo view('finalizar', [$venta, $uri->getPath()]);
        echo view('footer');
    }

	public function get_preference(){
        if($this->request->isAJAX()) {
            $modelo = new VentaModel();
            $data = [];
            $data['id_producto'] = $this->request->getPost('id_producto');
            $data['qty'] = $this->request->getPost('quantity');
            $modelo->save($data);
            $data['id_venta'] = $modelo->getInsertID();
            \MercadoPago\SDK::setAccessToken(ACCESS_TOKEN_MP);
            $preference = new \MercadoPago\Preference();
            $preference->external_reference = $data['id_venta'];
            $item = new \MercadoPago\Item();
            $item->title = $this->request->getPost('description');//$data->description
            $item->quantity = $this->request->getPost('quantity');//$data->quantity;
            $item->unit_price = $this->request->getPost('unit_price');//data->price;
            $preference->items = array($item);
            $preference->back_urls = array(
                "success" => base_url("feedback"),
                "failure" => base_url("feedback"),
                "pending" => base_url("feedback")
            );
            $preference->auto_return = "approved";
            $preference->save();

            $response = array(
                'id' => $preference->id,
            );
            $data['preference_id'] = $preference->id;
            $modelo->save($data);
            return $this->response->setJSON($response);
        } else {
            redirect('home');
        }
    }

}
