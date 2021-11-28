<?php

namespace AHT\FastOrder\Controller\Index;

class Search extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;

    /**
     * @param \Magento\Framework\Serialize\Serializer\Json
     */
    private $json;

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param \Magento\Framework\Controller\Result\JsonFactory
     */
    private $jsonFactory;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param \Magento\Catalog\Helper\Image
     */
    private $imageHelper;

    /**
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @param \Magento\Catalog\Model\ProductRepository
     */
    private $productRepository;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Serialize\Serializer\Json $json,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Catalog\Model\ProductRepository $productRepository
    ) {
        $this->json = $json;
        $this->collectionFactory = $collectionFactory;
        $this->jsonFactory = $jsonFactory;
        $this->storeManager = $storeManager;
        $this->imageHelper = $imageHelper;
        $this->priceCurrency = $priceCurrency;
        $this->productRepository = $productRepository;
        return parent::__construct($context);
    }
    /**
     * View page action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getContent();
        $data = $this->json->unserialize($data);

        $productCollection = $this->collectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('name', ['like' => '%' . $data['search'] . '%'])
            ->setPageSize(10);
        $productCollectionArray = $productCollection->toArray();

        $baseUrl = $this->storeManager->getStore()->getBaseUrl();
        $currencySymbol = $this->priceCurrency->getCurrencySymbol();

        foreach ($productCollectionArray as $key => $value) {
            $productCollectionArray[$key]['url_key_html'] = $baseUrl . $value['url_key'] . '.html';
            $productCollectionArray[$key]['currencySymbol'] = $currencySymbol;
            $productCollectionArray[$key]['price'] = number_format($value['price'], 2, '.', ',');
            $productCollectionArray[$key]['search_image_html'] = $this->imageHelper->init(
                $this->productRepository->get($value['sku']),
                'product_thumbnail_image'
            )
                ->setImageFile($value['small_image'])
                ->getUrl();
        }

        $jsonResult = $this->jsonFactory->create();
        $jsonResult->setData([
            'data' => $productCollectionArray
        ]);
        // return data = response (in fastorder.js)
        return $jsonResult;
    }
}
