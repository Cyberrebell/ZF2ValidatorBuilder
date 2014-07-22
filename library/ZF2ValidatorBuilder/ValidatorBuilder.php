<?php

namespace ZF2ValidatorBuilder;

/**
 * Toolset to apply InputFilters to form elements
 * @author Cyberrebell
 *
 */
class ValidatorBuilder
{
    protected $form;
    
    /**
     * @param \Zend\Form\Form $form
     */
    function __construct(\Zend\Form\Form $form) {
        $this->form = $form;
    }
    
    /**
     * Set element required
     * frontend & backend validation
     * @param \Zend\Form\Element $element
     */
    public function setElementRequired(\Zend\Form\Element $element) {
        $element->setAttribute('required', 'true'); //set browser validation
        $this->form->getInputFilter()->get($element->getAttribute('name'))->setAllowEmpty(false); //set backend requirement
    }
    
    public function attachEmailAddressValidator(\Zend\Form\Element $element) {
        $elementValidatorChain = $this->form->getElementValidatorChain($element);
        $elementValidatorChain->attachByName('EmailAddress');
    }
    
    public function attachEqualsValidator(\Zend\Form\Element $element, $value) {
        $elementValidatorChain = $this->form->getElementValidatorChain($element);
        $elementValidatorChain->attachByName('Regex', ['pattern' => '/' . $value . '/']);
    }
    
    public function attachIdenticalValidator(\Zend\Form\Element $elementOne, \Zend\Form\Element $elementTwo) {
        $elementTwoValidatorChain = $this->form->getElementValidatorChain($elementTwo);
        $elementTwoValidatorChain->attachByName('Identical', ['token' => $elementOne->getAttribute('name'), 'strict' => true]);
    }
    
    public function attachStringLengthValidator(\Zend\Form\Element $element, $min = NULL, $max = NULL) {
        $elementValidatorChain = $this->form->getElementValidatorChain($element);
        $options = [];
        if ($min !== NULL) {
            $options['min'] = $min;
        }
        if ($max !== NULL) {
            $options['max'] = $max;
        }
        $elementValidatorChain->attachByName('StringLength', $options);
    }
    
    /**
     * Get elements validator chain
     * @param \Zend\Form\Element $element
     * @return \Zend\Validator\ValidatorChain
     */
    protected function getElementValidatorChain(\Zend\Form\Element $element) {
        $elementName = $element->getAttribute('name');
        return $this->form->getInputFilter()->get($elementName)->getValidatorChain();
    }
}
