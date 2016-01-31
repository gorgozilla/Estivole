<fieldset>
    <legend><?php echo JText::_( 'BEEZ_20_COPY_TERMS_OF_SERVICE')?></legend>
    <p><input type="checkbox" />
       <?php echo JText::_()?>  </p>
    <?php if ($this->params->get('show_age_checkbox')) : ?>
       <p><input type="checkbox" />
           <?php echo JText::_('BEEZ_20_COPY_AGE')?> </p>
    <?php endif; ?>
</fieldset>