<div>

    @if ($modalProductos == true)

        <!-- MODAL PARA CARGAR UN NUEVO PRODUCTO -->
        <div class="modal fade show" id="modal-default"
            style="display: block; padding-right: 17px;background-color:rgba(0, 0, 0, 0.5)" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title"> <strong> NUEVO PRODUCTO </strong> </h4>
                        <button type="button" class="close" wire:click='modalProductosOn'>
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="m-3">

                            <div class="text-center form-group">
                                <h4>{{ $producto->descripcion ?? '' }}</h4>
                            </div>

                            <div class="pt-4 row">
                                <div class="col">
                                    <select class="form-control" aria-label="Default select example"
                                        wire:model='categoria' wire:change='selTipo'>
                                        <option selected>Categoria</option>
                                        @foreach ($categorias as $ca)
                                            <option value="{{ $ca->id }}">{{ $ca->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                                
                            @if ($formDes)
                                <div class="pt-4 row">
                                    <div class="col-md">
                                        <select class="form-control" aria-label="Default select example"
                                            wire:model='subcategoria' wire:change='selSubTipo'>
                                            <option selected>Subcategoria</option>
                                            @foreach ($subcategorias as $sub)
                                                <option value="{{ $sub[0] }}">
                                                    {{ $sub[1] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                            @endif
                      

                            @if ($formProd)

                                <div class="pt-4 row">
                                    <div class="col-md">
                                        <select class="form-control" aria-label="Default select example"
                                            wire:model='subcategoria'>
                                            <option selected>Subcategoria</option>
                                            @foreach ($subcategorias as $sub)
                                                <option value="{{ $sub->id }}">
                                                    {{ $sub->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            @endif


                            {{-- Descripcion y Codigo --}}
                            <div class="pt-4 row">
                                <div class="col-md-8">
                                    <input type="text" class="form-control" wire:model='descripcion'
                                        placeholder="Producto" />
                                </div>

                                <div class="col-md-4">
                                    <input type="text" class="form-control" wire:model='codigo'
                                        placeholder="Codigo" />
                                </div>
                            </div>

                            @if ($formProd)
                                {{-- Costo y Precio de venta --}}
                                <div class="pt-4 row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" wire:model.live='costo'
                                                placeholder="Precio de costo">
                                            <div class="input-group-append">
                                                <span class="input-group-text">.00</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" wire:model.live='precioVenta'
                                                placeholder="Precio de venta">
                                            <div class="input-group-append">
                                                <span class="input-group-text">.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @can('stock')
                                
                     
                            @if ($formDes)
                                {{-- Porcentaje o Monto --}}
                                <div class="pt-4 row">
                                    @if ($tipoDes)
                                        <div class="col-md">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                                <input type="text" class="form-control" wire:model='porcentaje'
                                                    placeholder="Porcentaje">

                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="text" class="form-control" wire:model='monto'
                                                    placeholder="Monto">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif



                            {{-- Codigo de barra y Stock --}}
                            <div class="pt-4 row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" wire:model='cod_barra'
                                        placeholder="Codigo de barras" />
                                </div>


                                <div class="col-md-6">
                                    <input type="number" class="form-control" wire:model='stock'
                                        placeholder="Stock inicial" />
                                </div>
                            </div>
                            @endcan


                            @if ($formProd)
                                {{-- Proveedor --}}
                                <div class="pt-4 row">
                                    <div class="col">
                                        <select class="form-control" aria-label="Default select example"
                                            wire:model='proveedor'>
                                            @foreach ($proveedores as $pro)
                                                <option value="{{ $pro->id }}">
                                                    {{ $pro->perfiles->personas->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif



                        </div>


                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" wire:click='modalProductosOn'>Cerrar</button>
                        <button type="button" class="btn btn-primary" wire:click='saveproduct'>Guardar</button>
                    </div>
                    </form>
                </div>

            </div>

        </div>
    @endif

    @script
    <script>
        $wire.on('nonDesc', (event) => {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No tienes autorizacion para crear descuentos",
            });
        });
    </script>
@endscript
</div>
