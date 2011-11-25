<div class="ce_oi_searchform">
    <form method="post" action="<?=$this->postURL;?>">
        <div class="formbody">
            <label for="oi_sf_plz">Postleitzahl / Ort</label>
            <select name="oi_sf_plz" id="oi_sf_plz">
                <option value="">Bitte auswählen</option>
            <? foreach( $this->oi_sf_plz_vals as $plz => $ort ) { ?>
                <option value="<?=$plz;?>" <?if($plz==$this->oi_sf_plz_selected){?>selected="selected"<?}?>><?=$plz;?> <?=$ort;?></option>
            <? } ?>
            </select>
            <br />
            <label for="oi_sf_radius">Objekte in der Nähe von</label>
            <input type="text" class="text" name="oi_sf_radius" id="oi_sf_radius" value="<?=$this->oi_sf_radius;?>" />
            <select name="oi_sf_radius_km" id="oi_sf_radius_km">
                <?php foreach( $this->aRadians as $val ) { ?>
                <option value="<?=$val;?>" <? if( $this->oi_sf_radius_km==$val ) { ?>selected="selected"<? } ?>><?=$val;?>km</option>
                <?php } ?>
            </select>
            
            <input type="submit" class="submit" value="Wunschobjekte anzeigen" />
        </div>
    </form>
</div>