<div id="tl_buttons">
    <a onclick="Backend.getScrollOffset();" accesskey="b" title="Zurück" class="header_back" href="contao/main.php?do=oi_files">
        <? echo $GLOBALS['TL_LANG']['MSC']['backBT']; ?>
    </a>
</div>

<h2 class="sub_headline">
    <? echo sprintf($GLOBALS['TL_LANG']['be_oi_objects']['head'],$this->pool['name']); ?>
</h2>

<div class="tl_listing_container list_view">

    <table cellspacing="0" cellpadding="0" class="tl_listing">


    <?php
    foreach( $this->entries['anbieter'] as $anbieter ) {
        foreach( $anbieter['immobilie'] as $immobilie ) {
    ?>
    
    <tr onmouseout="Theme.hoverRow(this, 0);" onmouseover="Theme.hoverRow(this, 1);">
        <td class="tl_file_list">
            <div class="tl_oi_object_entry">
                <div class="img">
                    {{image::<? echo $immobilie['__contao__']['mainImage']; ?>?width=50&height=50}}
                </div>
                <div class="text">
                    <div class="title">
                        <? echo $immobilie['verwaltung_techn'][0]['objektnr_extern'][0]['_data']; ?> - 
                        <? echo substr($immobilie['freitexte'][0]['objekttitel'][0]['_data'],0,60); ?>
                    </div>                  
                    <div class="desc">
                        <div>
                            <? echo $immobilie['geo'][0]['plz'][0]['_data']; ?> <? echo $immobilie['geo'][0]['ort'][0]['_data']; ?>, 
                            <?
                                if( !empty($immobilie['flaechen'][0]['wohnflaeche'][0]['_data']) ) {
                                    echo (int)$immobilie['flaechen'][0]['wohnflaeche'][0]['_data'];
                                } else if( !empty($immobilie['flaechen'][0]['grundstuecksflaeche'][0]['_data']) ) {
                                    echo (int)$immobilie['flaechen'][0]['grundstuecksflaeche'][0]['_data'];
                                } else if( !empty($immobilie['flaechen'][0]['gesamtflaeche'][0]['_data']) ) {
                                    echo (int)$immobilie['flaechen'][0]['gesamtflaeche'][0]['_data'];
                                } else if( !empty($immobilie['flaechen'][0]['bueroflaeche'][0]['_data']) ) {
                                    echo (int)$immobilie['flaechen'][0]['bueroflaeche'][0]['_data'];
                                }
                            ?>m²
                        </div>
                        <div>                       
                            <? echo $GLOBALS['TL_LANG']['be_oi_objects']['miete']; ?>: <? echo empty($immobilie['preise'][0]['nettokaltmiete'][0]['_data']) ? '-' : number_format($immobilie['preise'][0]['nettokaltmiete'][0]['_data'],2,',','.').$immobilie['__contao__']['currencyCode']; ?> 
                            / <? echo $GLOBALS['TL_LANG']['be_oi_objects']['kaufpreis']; ?>: <? echo empty($immobilie['preise'][0]['kaufpreis'][0]['_data']) ? '-' : number_format($immobilie['preise'][0]['kaufpreis'][0]['_data'],2,',','.').$immobilie['__contao__']['currencyCode']; ?>
                            / <? echo $GLOBALS['TL_LANG']['be_oi_objects']['provision']; ?>: <? echo $immobilie['preise'][0]['aussen_courtage'][0]['_data']; ?>
                        </div>            
                    </div>
                </div>
                <div class="indicators">
                    <div class="geo <? if( empty($immobilie['geo'][0]['geokoordinaten']) ) { ?>inactive<?php } ?>" title="<? if( !empty($immobilie['geo'][0]['geokoordinaten']) ) { ?>Für dieses Objekt sind geographische Koordinaten vorhanden<?php } ?>"></div>
                </div>
                <?php if( !empty($immobilie['__contao__']['detailLink']) ) { ?>
                <div class="link">
                    <a class="header_back" href="<?php echo $immobilie['__contao__']['detailLink']; ?>" target="_blank">
                        <? echo $GLOBALS['TL_LANG']['be_oi_objects']['objectLink']; ?>
                    </a>
                </div>
                <?php } ?>
            </div>        
        </td>
    </tr>
    <?php
        
        }
    }
    ?>

    </table>

</div>