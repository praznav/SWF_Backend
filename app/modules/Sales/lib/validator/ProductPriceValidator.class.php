<?php

class Sales_ProductPriceValidator extends AgaviValidator
{
    protected function validate()
    {
        $price = $this->getData($this->getArgument());
        if (!isset($price))
        {
            $this->throwError('not_set');
            return false;
        }

        if ($price < 0)
        {
            $this->throwError('too_low');
            return false;
        }

        if ($price > 9999999999.99)
        {
            $this->throwError('too_high');
            return false;
        }

        error_log("Product price is good...");

        $this->export($price);
        return true;
    }
}

?>
