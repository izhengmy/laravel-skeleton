<?php

namespace Override\Overtrue\EasySms\Strategies;

use Overtrue\EasySms\Contracts\StrategyInterface;

class OrderStrategy implements StrategyInterface
{
    /**
     * Apply the strategy and return result.
     *
     * @param array $gateways
     *
     * @return array
     */
    public function apply(array $gateways)
    {
        $gateways = array_keys($gateways);

        if (! app()->environment('local')) {
            if (false !== ($index = array_search('local', $gateways))) {
                unset($gateways[$index]);
                $gateways = array_values($gateways);
            }
        }

        return $gateways;
    }
}
