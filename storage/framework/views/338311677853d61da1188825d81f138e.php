<?php echo $__env->make('web.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<body class="d-flex flex-column min-vh-100">

    
    <main class="flex-grow-1">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <?php echo $__env->make('web.layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    
    <script src="<?php echo e(asset('web/js/jquery_custom.js')); ?>"></script>
    <script src="<?php echo e(asset('web/js/typed.js')); ?>"></script>
    <?php echo $__env->yieldContent('script'); ?>
</body>
<?php /**PATH C:\xampp\htdocs\Online_Saptari\resources\views/web/layouts/master.blade.php ENDPATH**/ ?>