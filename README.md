# Construction step:

1. create normal page
    - controller
        + Index/Index.php
    - layout
        + fastorder_index_index.xml
    - block
        + Index.php
    - templates
        + index.phtml

2. create custom component with Magento_Ui/js/core/app
    - define KO initialisation code (index.phtml)
    - crete js component file (web/js/fastorder.js)
    - create template file (web/template/fastorder.html)