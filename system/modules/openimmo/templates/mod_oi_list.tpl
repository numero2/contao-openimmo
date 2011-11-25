<div class="mod_oi_list">

    <ul class="typenav">
        <li>
            <?php if( $this->typ != 'miete' ) { ?><a href="<? echo $this->mietURL; ?>"><? } ?>
                Miete
            <?php if( $this->typ != 'miete' ) { ?></a><? } ?>
        </li>
        <li>
            <?php if( $this->typ != 'kauf' ) { ?><a href="<? echo $this->kaufURL; ?>"><? } ?>
                Kauf
            <?php if( $this->typ != 'kauf' ) { ?></a><? } ?>
        </li>
    </ul>

    <?php
    if( $this->hasMatchingObjects ) {
    ?>
        <?php if( $this->sf_radius_point['name'] ) { ?>
        <h2 class="objects_near_point">
            Folgende Objekte wurden in der Nähe von <?=$this->sf_radius_point['name'];?> gefunden:
        </h2>
        <?php } ?>
    <?
        foreach( $this->pools as $poolIdx => $pool ) {
            foreach( $this->entries[$poolIdx]['anbieter'][0]['immobilie'] as $immobilie ) {
    ?>
            <div class="tl_oi_object_entry">
                <div class="img">
                    <?php if( !empty($immobilie['__contao__']['detailLink']) ) { ?><a href="<?php echo $immobilie['__contao__']['detailLink']; ?>"><?php } ?>
                    {{image::<? echo $immobilie['__contao__']['mainImage']; ?>?width=120&height=120}}
                    <?php if( !empty($immobilie['__contao__']['detailLink']) ) { ?></a><?php } ?>
                </div>
                <div class="text">
                    <div class="title">
                        <? echo $immobilie['verwaltung_techn'][0]['objektnr_extern'][0]['_data']; ?> - 
                        <? echo $immobilie['freitexte'][0]['objekttitel'][0]['_data']; ?>
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
                            <? if( !empty($immobilie['preise'][0]['nettokaltmiete'][0]['_data']) ) { ?>
                                Miete: <? echo number_format($immobilie['preise'][0]['nettokaltmiete'][0]['_data'],2,',','.'); ?> &euro;
                            <? } else { ?>
                                Kaufpreis: <? echo number_format($immobilie['preise'][0]['kaufpreis'][0]['_data'],2,',','.'); ?> &euro;
                            <? } ?>
                              / Provision: <? echo $immobilie['preise'][0]['aussen_courtage'][0]['_data']; ?>
                        </div>            
                    </div>
                    <?php if( !empty($immobilie['__contao__']['distance']) ) { ?>
                    <div class="distance">
                        Entfernung ca. <?=number_format($immobilie['__contao__']['distance'],2,',','.');?>km
                    </div>
                    <?php } ?>
                    <?php if( !empty($immobilie['__contao__']['detailLink']) ) { ?>
                    <div class="link">
                        <a href="<?php echo $immobilie['__contao__']['detailLink']; ?>">Details anzeigen</a>
                    </div>
                    <?php } ?>       
                </div>
            </div>
    <?php
            
            }
        }
    } else {
    ?>
        <div class="noresults">
            Leider wurden keine passenden Angebote gefunden.
        </div>

    <? } ?>
</div>