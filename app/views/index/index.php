<div class='row'>
    <div class='col-md-4'>          
        <div class='well' style='height: 260px;'>
            <h4><?=FORM_FROM_TITLE?> <small>(<a id='find_me' href='#'><?=FIND_ME?></a>)</small></h4>
            <p>
                <!--<input class='form-control input-sm' id='from' name="from" autocomplete="off" placeholder='<?=PLACEHOLDER?>' value="51 вулиця Гарматна, Киев, город Киев, Украина" type='text' />-->
                <input class='form-control input-sm' id='from' name="from" autocomplete="off" placeholder='<?=PLACEHOLDER?>' maxlength="80" type='text' />
            </p>
            <h4><?=FORM_FROM_TO?></h4>
            <p>
                <!--<input class='form-control input-sm' id='to' name="to" autocomplete="off" placeholder='<?=PLACEHOLDER?>' value="19 вулиця Олени Теліги, Киев, город Киев, Украина" type='text' />-->
                <input class='form-control input-sm' id='to' name="to" autocomplete="off" placeholder='<?=PLACEHOLDER?>' maxlength="80" type='text' />
            </p>
            <p>
            <div style="min-height: 30px;">
                <div class='pull-right'>
                    <button class='btn btn-success btn-sm' id='search'>
                        &nbsp;
                        <i class='glyphicon glyphicon-search'></i>
                        &nbsp;
                    </button>
                    <!--<button class='btn btn-default btn-sm' id='reset'>
                        <i class='glyphicon glyphicon-repeat'></i>
                    </button>-->
                </div>
                <div class='pull-left'>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default btn-sm active">
                            <input type="checkbox" id="bus" autocomplete="off" checked> <?=TRANSPORT_BUS?>
                        </label>
                        <label class="btn btn-default btn-sm active">
                            <input type="checkbox" id="trol" autocomplete="off" checked> <?=TRANSPORT_TROL?>
                        </label>
                    </div>
                </div>
            </div>
            </p>
            <p>
            <div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="direct" checked /> <?=DIRECT?>
                    </label>
                </div>
            </div>
            </p>
            <br />
            
        </div>
        
        <div id="result_box"></div>
    </div>
    <div class='col-md-8'>
        <noscript>
            <div class='alert alert-info'>
                <h4>Your JavaScript is disabled</h4>
                <p>Please enable JavaScript to view the map.</p>
            </div>
        </noscript>
        <div id='map'></div>          
        <div id='map-loader'></div>                
    </div>    
</div>