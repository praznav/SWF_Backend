<?php

class Sales_SalesManagerModel extends ShoppingwithfriendsSalesBaseModel
{
    public function registerInterest($username, $productName, $maxPrice)
    {
        // Make sure the username, product name and product price are set
        if (!isset($username) || !isset($productName) || !isset($maxPrice))
        {
            return 1;
        }

        // Try and find a user in the database matching the requesting user
        $user = UserQuery::create()->findOneByUsername($username);

        // Make sure we actually found a user
        if (!isset($user))
        {
            return 2;
        }

        // Try and find a matching product
        $product = ProductQuery::create()->findOneByProductName($productName);

        // If we did not find one, then no interest can exist
        // So go ahead and create the corresponding product and interest
        if (!isset($product))
        {
            $product = new Product();
            $product->setProductName($productName);
            $product->save();

            $interest = new Interest();
            $interest->setProductId($product->getProductId());
            $interest->setMaxPrice($maxPrice);
            $interest->setUserId($user->getUserId());
            $interest->save();

            return 0;
        }

        // See if an interest already exists
        $interest = InterestQuery::create()->filterByUserId($user->getUserId())->filterByProductId($product->getProductId())->findOne();

        // If an interest already exists, then we do not need to create one
        if (isset($interest))
        {
            return 3;
        }

        // Otherwise, create the new interest with the existing product
        $interest = new Interest();
        $interest->setProductId($product->getProductId());
        $interest->setMaxPrice($maxPrice);
        $interest->setUserId($user->getUserId());
        $interest->save();

        return 0;
    }

    public function registerSale($username, $productName, $productPrice, $productLocation)
    {
        // Make sure the username, product name, product price and product location are set
        if (!isset($username) || !isset($productName) || !isset($productPrice) || !isset($productLocation))
        {
            return 1;
        }

        // Try and find a user in the database matching the requesting user
        $user = UserQuery::create()->findOneByUsername($username);

        // Make sure we actually found a user
        if (!isset($user))
        {
            return 2;
        }

        // Try and find a matching product
        $product = ProductQuery::create()->findOneByProductName($productName);

        // If we did not find one, then no sale can exist
        // So go ahead and create the corresponding product and sale
        if (!isset($product))
        {
            $product = new Product();
            $product->setProductName($productName);
            $product->save();

            $sale = new Sale();
            $sale->setProductId($product->getProductId());
            $sale->setPrice($productPrice);
            $sale->setLocation($productLocation);
            $sale->setUserId($user->getUserId());
            $sale->save();

            return 0;
        }

        // See if a sale already exists
        $sale = SaleQuery::create()->filterByUserId($user->getUserId())->filterByProductId($product->getProductId())->filterByLocation($productLocation)->findOne();

        // If a sale already exists, then we do not need to create one
        if (isset($sale))
        {
            return 3;
        }

        // Otherwise, create the new sale with the existing product
        $sale = new Sale();
        $sale->setProductId($product->getProductId());
        $sale->setPrice($productPrice);
        $sale->setLocation($productLocation);
        $sale->setUserId($user->getUserId());
        $sale->save();


        return 0;
    }

}

?>
