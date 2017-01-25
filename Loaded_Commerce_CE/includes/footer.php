            <!-- begin #footer -->
            <div id="footer" class="footer"> Copyright &copy; 2016 <a target="_blank" href="http://www.loadedcommerce.com/">Loaded Commerce, LLC</a>, Powered by <a target="_blank" href="http://www.loadedcommerce.com">Loaded Commerce</a> </div>
        </div>
        <!-- end #footer --> 

        <!-- begin theme-panel -->
        <!--div class="theme-panel"> <a href="javascript:;" data-click="theme-panel-expand" class="theme-collapse-btn"><i class="fa fa-cog"></i></a>
            <div class="theme-panel-content">
                <h5 class="m-t-0">Quick Links</h5>
                <div class="divider"></div>
                <div class="row m-t-10">
                    <div class="col-md-5 control-label double-line"></div>
                    <div class="col-md-7"> Some quick and handy stuff if needed </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-12"> <a href="#" class="btn btn-inverse btn-block btn-sm" data-click="reset-local-storage"><i class="fa fa-refresh m-r-3"></i> Update</a> </div>
                </div>
            </div>
        </div -->
        <!-- end theme-panel --> 

        <!-- begin scroll to top btn --> 
        <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a> 
        <!-- end scroll to top btn -->
        </div>
        <!-- end page container --> 

        <!-- ================== BEGIN BASE JS ================== --> 
        <script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script> 
        <script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script> 
        <script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script> 
        <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script> 
        <!--[if lt IE 9]>
        <script src="assets/crossbrowserjs/html5shiv.js"></script>
        <script src="assets/crossbrowserjs/respond.min.js"></script>
        <script src="assets/crossbrowserjs/excanvas.min.js"></script>
        <![endif]--> 
        <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script> 

        <!-- ================== END BASE JS ================== --> 

        <!-- ================== BEGIN PAGE LEVEL JS ================== --> 
        <script type="text/javascript" src="assets/plugins/filestyle/bootstrap-filestyle.min.js" charset="utf-8"></script>
        <script src="assets/js/apps.min.js"></script> 
        <!-- ================== END PAGE LEVEL JS ================== --> 
        <!-- header search -->
        <div id="popoverProductsSearch" class="hide">
            <form role="form" id="ProductSearch" method="post" action="http://kiran.trhsecure.com/b2blatest/admin/categories.php?osCAdminID=11aa057bb6540bce70a15f4c7ac4fc71">
                <div class="input-group">
                    <input type="text" class="form-control" id="search" name="search" placeholder="Search Products">
                <a href="javascript:document.getElementById('ProductSearch').submit();" class="btn btn-default input-group-addon">GO</a> </div>
                <input type="hidden" name="osCAdminID" value="11aa057bb6540bce70a15f4c7ac4fc71">
            </form>
        </div>
        <div id="popoverCustomerSearch" class="hide">
            <form role="form" id="CustomerSearch" method="GET" action="http://kiran.trhsecure.com/b2blatest/admin/customers.php?osCAdminID=11aa057bb6540bce70a15f4c7ac4fc71">
                <div class="input-group">
                    <input type="text" class="form-control" id="search" name="search" placeholder="Search Customer">
                <a href="javascript:document.getElementById('CustomerSearch').submit();" class="btn btn-default input-group-addon">GO</a> </div>
                <input type="hidden" name="osCAdminID" value="11aa057bb6540bce70a15f4c7ac4fc71">
            </form>
        </div>
        <div id="popoverOrderSearch" class="hide">
            <form role="form" id="OrderSearch" method="GET" action="http://kiran.trhsecure.com/b2blatest/admin/orders.php?osCAdminID=11aa057bb6540bce70a15f4c7ac4fc71">
                <div class="input-group">
                    <input type="text" class="form-control" id="oID" name="oID" placeholder="Search Orders">
                    <input type="hidden" name="action" value="edit">
                <a href="javascript:document.getElementById('OrderSearch').submit();" class="btn btn-default input-group-addon">GO</a> </div>
                <input type="hidden" name="osCAdminID" value="11aa057bb6540bce70a15f4c7ac4fc71">
            </form>
        </div>
        <div id="popoverPagesSearch" class="hide">
            <form role="form" id="PageSearch" method="post" action="http://kiran.trhsecure.com/b2blatest/admin/pages.php?osCAdminID=11aa057bb6540bce70a15f4c7ac4fc71">
                <div class="input-group">
                    <input type="text" class="form-control" id="search" name="search" placeholder="Search Pages">
                <a href="javascript:document.getElementById('PageSearch').submit();" class="btn btn-default input-group-addon">GO</a> </div>
                <input type="hidden" name="osCAdminID" value="11aa057bb6540bce70a15f4c7ac4fc71">
            </form>
        </div>
        <!-- header search --> 
        <script>  
            $('#ProductsPopover').popover({
                html: true,
                placement : 'auto bottom',
                content: $('#popoverProductsSearch').html(),
            }).on('shown.bs.popover', function() {
                $('#ProductsPopover').parent().find('input').focus();
            });
            $('#CustomerPopover').popover({
                html: true,
                placement : 'auto bottom',
                content: $('#popoverCustomerSearch').html(),
            }).on('shown.bs.popover', function() {
                $('#CustomerPopover').parent().find('input').focus();
            });
            $('#OrdersPopover').popover({
                html: true,
                placement : 'auto bottom',
                content: $('#popoverOrderSearch').html(),
            }).on('shown.bs.popover', function() {
                $('#OrdersPopover').parent().find('input').focus();
            });
            $('#PagesPopover').popover({
                html: true,
                placement : 'auto bottom',
                content: $('#popoverPagesSearch').html(),
            }).on('shown.bs.popover', function() {
                $('#PagesPopover').parent().find('input').focus();
            });   
            $(document).ready(function() {
                App.init();
            });
        </script>