<?php
class LogicSpot_AdaptiveResize_Model_Catalog_Product_Image extends Mage_Catalog_Model_Product_Image
{
    const POSTION_TOP     = 'top';
    const POSITION_BOTTOM = 'bottom';
    const POSITION_CENTER = 'center';

    /**
     * Crop position from top
     *
     * @var float
     */
    protected $_topRate = 0.5;

    /**
     * Crop position from bootom
     *
     * @var float
     */
    protected $_bottomRate = 0.5;

    /**
     * Adaptive Resize
     *
     * @return LogicSpot_AdaptiveResize_Model_Catalog_Product_Image
     */
    public function adaptiveResize()
    {
        if (is_null($this->getWidth())) {
            return $this;
        }

        if (is_null($this->getHeight())) {
            $this->setHeight($this->getWidth());
        }

        $processor = $this->getImageProcessor();

        $currentRatio = $processor->getOriginalWidth() / $processor->getOriginalHeight();
        $targetRatio = $this->getWidth() / $this->getHeight();

        //$processor->keepFrame(true);

        if ($targetRatio > $currentRatio) {
            $processor->resize($this->getWidth(), null);
        } else {
            $processor->resize(null, $this->getHeight());
        }

        $diffWidth  = $processor->getOriginalWidth() - $this->getWidth();
        $diffHeight = $processor->getOriginalHeight() - $this->getHeight();

        $processor->crop(
            floor($diffHeight * $this->_topRate),
            floor($diffWidth / 2),
            ceil($diffWidth / 2),
            ceil($diffHeight * $this->_bottomRate)
        );

        return $this;
    }

    /**
     * Set crop position
     *
     * @param string $position top, bottom or center
     *
     * @return LogicSpot_AdaptiveResize_Model_Catalog_Product_Image
     */
    public function setCropPosition($position)
    {
        switch ($position) {
            case self::POSTION_TOP:
                $this->_topRate    = 0;
                $this->_bottomRate = 1;
                break;
            case self::POSITION_BOTTOM:
                $this->_topRate    = 1;
                $this->_bottomRate = 0;
                break;
            default:
                $this->_topRate    = 0.5;
                $this->_bottomRate = 0.5;
        }
        return $this;
    }

}
