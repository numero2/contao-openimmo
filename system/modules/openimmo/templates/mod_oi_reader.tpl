<div class="mod_oi_reader">
<?php if( $this->backLink ) { ?>
<div class="backLink">
    <a href="<? echo $this->backLink; ?>">zurück</a>
</div>
<?php } ?>


<?php if( !empty($this->entry['__contao__']['mainImage']) ) { ?>
<h3>Bilder</h3>
<div style="height:200px;">
    <div style="float:left;">
    {{image::<? echo $this->entry['__contao__']['mainImage']; ?>?width=200}}
    </div>
    
    <?php if( !empty($this->entry['anhaenge'][0]['anhang']) ) { ?>
        <?php 
            foreach( $this->entry['anhaenge'][0]['anhang'] as $i => $v) { 
            
                if( $v['_attributes']['gruppe'] != 'BILD' )
                    continue;
        ?>
            <div style="float:left; margin-left: 10px;" title="<? echo !empty($v['anhangtitel'][0]['_data']) ? $v['anhangtitel'][0]['_data'] : ''; ?>">
                {{image::<? echo $this->pool['dir'].DIRECTORY_SEPARATOR.$v['daten'][0]['pfad'][0]['_data']; ?>?width=90&alt=<? echo !empty($v['anhangtitel'][0]['_data']) ? $v['anhangtitel'][0]['_data'] : ''; ?>}}
            </div>
        <?php } ?>
    <?php } ?>
</div>
<div class="clear"></div>
<?php } ?>


<h3>Allgemeine Angaben</h3>
<table cellspacing="0" cellpadding="2" border="1" width="100%">
<tr>
    <td>Objektart:</td>
    <td>
        <? // art des objekts ?>
        <? $k = array_keys($this->entry['objektkategorie'][0]['objektart'][0]); ?>
        <? echo $GLOBALS['TL_LANG']['tl_oi_labels']['objektkategorie']['objektart'][$k[0]]['_NAME_']; ?>
        
        <? // genauere typbezeichnung (falls vorhanden) ?>
        <? if( !empty($this->entry['objektkategorie'][0]['objektart'][0][$k[0]][0]['_attributes']) ) { ?>
            <? 
                $a = array_keys($this->entry['objektkategorie'][0]['objektart'][0][$k[0]][0]['_attributes']); 
                $l = $GLOBALS['TL_LANG']['tl_oi_labels']['objektkategorie']['objektart'][$k[0]]['_ATTR_'][$a[0]][$this->entry['objektkategorie'][0]['objektart'][0][$k[0]][0]['_attributes'][$a[0]]];
            ?>
            <? if( $l ) { ?>(<? echo $l; ?>)<? } ?>
        <? } ?>
    </td>
</tr>
<? if( !empty($this->entry['freitexte'][0]['lage'][0]['_data']) ) { ?>
<tr>
    <td>Lage:</td>
    <td>
        <? echo $this->entry['freitexte'][0]['lage'][0]['_data']; ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['freitexte'][0]['ausstatt_beschr'][0]['_data']) ) { ?>
<tr>
    <td>Ausstattung:</td>
    <td>
        <? echo $this->entry['freitexte'][0]['ausstatt_beschr'][0]['_data']; ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['freitexte'][0]['sonstige_angaben'][0]['_data']) ) { ?>
<tr>
    <td>Sonstige Angaben:</td>
    <td>
        <? echo $this->entry['freitexte'][0]['sonstige_angaben'][0]['_data']; ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['geo'][0]['regionaler_zusatz'][0]['_data']) ) { ?>
<tr>
    <td>Orstangaben:</td>
    <td>
        <? echo $this->entry['geo'][0]['regionaler_zusatz'][0]['_data']; ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['preise'][0]['mietpreis_pro_qm'][0]['_data']) ) { ?>
<tr>
    <td>Miete pro m²:</td>
    <td>
        <? echo number_format($this->entry['preise'][0]['mietpreis_pro_qm'][0]['_data'],2,',','.'); ?> <? echo $this->entry['__contao__']['currencyCode'];?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['preise'][0]['nebenkosten'][0]['_data']) ) { ?>
<tr>
    <td>Nebenkosten:</td>
    <td>
        <? echo number_format($this->entry['preise'][0]['nebenkosten'][0]['_data'],2,',','.'); ?> <? echo $this->entry['__contao__']['currencyCode'];?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['preise'][0]['heizkosten'][0]['_data']) ) { ?>
<tr>
    <td>Heizkosten:</td>
    <td>
        <? echo number_format($this->entry['preise'][0]['heizkosten'][0]['_data'],2,',','.'); ?> <? echo $this->entry['__contao__']['currencyCode'];?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['flaechen'][0]['anzahl_zimmer'][0]['_data']) ) { ?>
<tr>
    <td>Zimmer / Räume:</td>
    <td>
        <? echo $this->entry['flaechen'][0]['anzahl_zimmer'][0]['_data']; ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['flaechen'][0]['anzahl_schlafzimmer'][0]['_data']) ) { ?>
<tr>
    <td>Schlafzimmer:</td>
    <td>
        <? echo $this->entry['flaechen'][0]['anzahl_schlafzimmer'][0]['_data']; ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['flaechen'][0]['anzahl_badezimmer'][0]['_data']) ) { ?>
<tr>
    <td>Badezimmer:</td>
    <td>
        <? echo $this->entry['flaechen'][0]['anzahl_badezimmer'][0]['_data']; ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['flaechen'][0]['anzahl_stellplaetze'][0]['_data']) ) { ?>
<tr>
    <td>Stellplätze:</td>
    <td>
        <? echo $this->entry['flaechen'][0]['anzahl_stellplaetze'][0]['_data']; ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['flaechen'][0]['verkaufsflaeche'][0]['_data']) ) { ?>
<tr>
    <td>Verkaufsfläche in m² ca.:</td>
    <td>
        <? echo $this->entry['flaechen'][0]['verkaufsflaeche'][0]['_data']; ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['flaechen'][0]['gesamtflaeche'][0]['_data']) ) { ?>
<tr>
    <td>Gesamtfläche m²:</td>
    <td>
        <? echo $this->entry['flaechen'][0]['gesamtflaeche'][0]['_data']; ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['flaechen'][0]['ladenflaeche'][0]['_data']) ) { ?>
<tr>
    <td>Gewerbefläche m² ca.:</td>
    <td>
        <? echo $this->entry['flaechen'][0]['ladenflaeche'][0]['_data']; ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['flaechen'][0]['wohnflaeche'][0]['_data']) ) { ?>
<tr>
    <td>Wohnfläche m²:</td>
    <td>
        <? echo $this->entry['flaechen'][0]['wohnflaeche'][0]['_data']; ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['flaechen'][0]['nutzflaeche'][0]['_data']) ) { ?>
<tr>
    <td>Nutzfläche m²:</td>
    <td>
        <? echo $this->entry['flaechen'][0]['nutzflaeche'][0]['_data']; ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['flaechen'][0]['lagerflaeche'][0]['_data']) ) { ?>
<tr>
    <td>Lagerfläche m²:</td>
    <td>
        <? echo $this->entry['flaechen'][0]['lagerflaeche'][0]['_data']; ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['flaechen'][0]['bueroflaeche'][0]['_data']) ) { ?>
<tr>
    <td>Bürofläche m²:</td>
    <td>
        <? echo $this->entry['flaechen'][0]['bueroflaeche'][0]['_data']; ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['flaechen'][0]['grundstuecksflaeche'][0]['_data']) ) { ?>
<tr>
    <td>Grundstücksfläche m²:</td>
    <td>
        <? echo $this->entry['flaechen'][0]['grundstuecksflaeche'][0]['_data']; ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['zustand_angaben'][0]['alter'][0]['_attributes']['alter_attr']) ) { ?>
<tr>
    <td>Alt-/Neubau:</td>
    <td>
        <? echo $GLOBALS['TL_LANG']['tl_oi_labels']['zustand_angaben']['alter']['_ATTR_']['alter_attr'][ $this->entry['zustand_angaben'][0]['alter'][0]['_attributes']['alter_attr'] ]; ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['verwaltung_objekt'][0]['verfuegbar_ab'][0]['_data']) ) { ?>
<tr>
    <td>Bezugstermin:</td>
    <td>
        <? echo $this->entry['verwaltung_objekt'][0]['verfuegbar_ab'][0]['_data']; ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['zustand_angaben'][0]['zustand'][0]['_attributes']['zustand_art']) ) { ?>
<tr>
    <td>Zustand:</td>
    <td>
        <? echo $GLOBALS['TL_LANG']['tl_oi_labels']['zustand_angaben']['zustand']['_ATTR_']['zustand_art'][ $this->entry['zustand_angaben'][0]['zustand'][0]['_attributes']['zustand_art'] ]; ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['zustand_angaben'][0]['baujahr'][0]['_data']) ) { ?>
<tr>
    <td>Baujahr:</td>
    <td>
        <? echo $this->entry['zustand_angaben'][0]['baujahr'][0]['_data']; ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['preise'][0]['kaution'][0]['_data']) ) { ?>
<tr>
    <td>Kaution:</td>
    <td>
        <? echo number_format($this->entry['preise'][0]['kaution'][0]['_data'],2,',','.'); ?> <? echo $this->entry['__contao__']['currencyCode'];?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['preise'][0]['nettokaltmiete'][0]['_data']) ) { ?>
<tr>
    <td>Miete:</td>
    <td>
        <? echo number_format($this->entry['preise'][0]['nettokaltmiete'][0]['_data'],2,',','.'); ?> <? echo $this->entry['__contao__']['currencyCode'];?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['preise'][0]['kaufpreis'][0]['_data']) ) { ?> 
<tr>
    <td>Kaufpreis:</td>
    <td>
        <? echo number_format($this->entry['preise'][0]['kaufpreis'][0]['_data'],2,',','.'); ?> <? echo $this->entry['__contao__']['currencyCode'];?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['preise'][0]['aussen_courtage'][0]['_data']) ) { ?>
<tr>
    <td>Mietprovision:</td>
    <td>
        <? echo $this->entry['preise'][0]['aussen_courtage'][0]['_data']; ?>
        <? if( !empty($this->entry['preise'][0]['aussen_courtage'][0]['_attributes']['mit_mwst']) ) { ?>
            inkl. MwSt.
        <? } ?>
    </td>
</tr>
<? } ?>
<? if( !empty($this->entry['freitexte'][0]['objektbeschreibung'][0]['_data']) ) { ?>
<tr>
    <td>Objektbeschreibung:</td>
    <td>
        <? echo $this->entry['freitexte'][0]['objektbeschreibung'][0]['_data']; ?>
    </td>
</tr>
<? } ?>
</table>


<?php if( !empty($this->entry['ausstattung']) ) { ?>
<h3>Ausstattung</h3>

<table cellspacing="0" cellpadding="2" border="1" width="100%">
<?php foreach( $this->entry['ausstattung'][0] as $groupType => $groupVals ) { ?>


<? if( !empty($groupVals[0]['_attributes']) ) { ?>
    <? 
    $aAttVals = array();
    foreach( $groupVals[0]['_attributes'] as $attType => $attVal ) {
        if( $attVal == 'false' )
            continue;
    
        if( $attVal == 'true' )                    
            $la = !empty($GLOBALS['TL_LANG']['tl_oi_labels']['ausstattung'][$groupType]['_NAME_']) ? $GLOBALS['TL_LANG']['tl_oi_labels']['ausstattung'][$groupType][$attType] : $attType;
        else
            $la = !empty($GLOBALS['TL_LANG']['tl_oi_labels']['ausstattung'][$groupType]['_ATTR_'][$attType][$attVal]) ? $GLOBALS['TL_LANG']['tl_oi_labels']['ausstattung'][$groupType]['_ATTR_'][$attType][$attVal] : $attVal;
            
        $aAttVals[] = $la;
    }
    
    if( !empty($aAttVals) ) {
    ?>
    <tr>
        <td>
            <? $l = !empty($GLOBALS['TL_LANG']['tl_oi_labels']['ausstattung'][$groupType]['_NAME_']) ? $GLOBALS['TL_LANG']['tl_oi_labels']['ausstattung'][$groupType]['_NAME_'] : $groupType; ?>
            <? echo $l; ?>
        </td>
        <td>            
            <span class="values">
                <? echo implode(', ',$aAttVals); ?>
            </span>
        </td>
    </tr>
    <? } ?>
<? } else { ?>
    
    <tr>
        <td>
            <? $l = !empty($GLOBALS['TL_LANG']['tl_oi_labels']['ausstattung'][$groupType]['_NAME_']) ? $GLOBALS['TL_LANG']['tl_oi_labels']['ausstattung'][$groupType]['_NAME_'] : $groupType; ?>
            <? echo $l; ?>
        </td>
        <td>            
            <? if( $groupVals[0]['_data'] == 'true' ) { ?>
                Ja
            <? } elseif( $groupVals[0]['_data'] == 'false' ) { ?>
                Nein
            <? } else { ?>
                <? echo $groupVals[0]['_data']; ?>
            <? } ?>
        </td>
    </tr>
    
<? } ?>

<?php } ?>
</table>
<?php } ?>



<?php if( $this->backLink ) { ?>
<div class="backLink">
    <a href="<? echo $this->backLink; ?>">zurück</a>
</div>
<?php } ?>

</div>