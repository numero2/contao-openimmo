<div class="ce_oi_object">
    <div class="image">
        <a href="<?php echo $this->entry['__contao__']['detailLink']; ?>">
            {{image::<?= $this->entry['__contao__']['mainImage']; ?>?width=150}}
        </a>
    </div>
    <div class="title">
        <?= $this->entry['freitexte'][0]['objekttitel'][0]['_data']; ?>
    </div>
    <div class="link">
        <a href="<?php echo $this->entry['__contao__']['detailLink']; ?>">
            Objekt ansehen
        </a>
    </div>
</div>