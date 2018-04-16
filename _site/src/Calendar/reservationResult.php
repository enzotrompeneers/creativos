										<div class="features-list">
                                            <ul>
                                                <li>
                                                    <div class="features-price-left">
                                                        <?=trad('precio_alquiler');?>:
                                                    </div>
                                                    <div class="features-price-right text-right">
                                                        <?=$rentCost?> &euro;
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="features-price-left">
                                                        <?=trad('coste_limpieza');?>:
                                                    </div>
                                                    <div class="features-price-right text-right">
                                                        <?=$cleaningCost?> &euro;
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="features-price-left">
                                                        <?=trad('linen');?>:
                                                    </div>
                                                    <div class="features-price-right text-right">
                                                        <?=$linenCost?> &euro;
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="features-price-left">
                                                        <?=trad('costes_administrativos');?>:
                                                    </div>
                                                    <div class="features-price-right text-right">
                                                        <?=$adminCost?> &euro;
                                                    </div>
                                                </li>
                                                </ul>
                                                <div class="price-total">
                                                    <ul>
                                                    <li>
                                                        <div class="features-price-left cost">
                                                            <?=trad('coste_total');?>:
                                                        </div>
                                                        <div class="features-price-right text-right amount">
                                                            <?=$totalCost?> &euro;
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="features-price-left">
                                                            <span><?=trad('deposito');?>:</span>
                                                        </div>
                                                        <div class="features-price-right text-right">
                                                            <span><?=$depositCost?> &euro; </span>
                                                        </div>
                                                    </li>
                                                    </ul>
                                                </div>
											</div>
											<input type="hidden" id="cost" value="true"  />