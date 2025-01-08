<ul class="<?php echo isset($class) ? $class : 'categorySelectJs' ?>">
<?php
if (count($teachLanguages) > 0) {
    foreach ($teachLanguages as $id => $language) {
        if ($language['tlang_available'] == 1) { 
            $langName = CommonHelper::renderHtml($language['tlang_name']);
        ?>
            <li class="categOptParentJS">
                <label class="select-option">
                    <?php if(!$langPage) { ?>
                        <input class="select-option__input" type="checkbox" name="teachs[]" value="<?php echo $id; ?>" <?php echo (in_array($id, $values)) ? "checked='checked'" : ''; ?>>
                        </span>
                    <?php } else { ?>
                        <input class="select-option__input" type="checkbox" name="teachs[]" value="<?php echo $id; ?>" <?php echo (in_array($id, $values)) ? "checked='checked'" : ''; ?> onClick="selectLanguage('<?php echo $language['tlang_slug']; ?>', '<?php echo $id; ?>')">
                    <?php } ?>
                        <span class="select-option__item categorySelectOptJs">
                            <?php echo CommonHelper::renderHtml($langName); ?>
                        </span>
                    </label>
                <?php 
                if (count($language['children']) > 0) {
                    $this->includeTemplate('_partial/teach-languages.php', ['teachLanguages' => $language['children'], 'values' => $values, 'class' => 'categOptParentJS', 'langPage' => $langPage]);
                }
                ?>
            </li>
            <?php
        }
    }
}
?>
</ul>