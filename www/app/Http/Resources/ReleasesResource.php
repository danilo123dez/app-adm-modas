<?php

namespace App\Http\Resources;

use App\Models\Stores;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ReleasesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if(empty($this->nome_loja) && !empty($this->loja_id)){
            $loja = Stores::find($this->loja_id);
            $nome_loja = $loja->nome;
            $uuid_loja = $loja->uuid;
            $loja_return = [
                'nome_loja' => $nome_loja,
                'loja_uuid' => $uuid_loja
            ];
        }elseif(!empty($this->nome_loja)){
            $nome_loja = $this->nome_loja;
            $uuid_loja = $this->loja_uuid;
            $loja_return = [
                'nome_loja' => $nome_loja,
                'loja_uuid' => $uuid_loja
            ];
        }else{
            $loja_return = '';
        }

        $return =  [
            'uuid' => $this->uuid,
            'boleta' => $this->boleta,
            'romaneio' => $this->romaneio,
            'cliente' => $this->cliente,
            'data_compra' => Carbon::parse($this->data_compra)->format('d/m/Y'),
            'data_vencimento' => Carbon::parse($this->data_vencimento)->format('d/m/Y'),
            'valor' => $this->valor,
        ];
        if(!empty($loja_return)){
            foreach($loja_return as $index => $value){
                $return[$index] = $value;
            }
        }
        return $return;
    }
}
