</div>
            <!-- /.container-fluid -->

        </div>
        <!-- End -->
               <?php /* <footer class="sticky-footer bg-white fixed-bottom">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span><a href="#">Developed by NexGenTz</a> </span>
                        </div>
                    </div>
                </footer> */  ?>
                <!-- End of Footer -->
    
            </div>
            <!-- End of Content Wrapper -->
    
        </div>
        <!-- End of Page Wrapper -->
    
        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
    
        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                      
                        <a class="btn btn-primary" href={{ route('logout') }}"
                        onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                       
                    </div>

                 


                </div>
            </div>
        </div>
        


     
    
        <!-- Bootstrap core JavaScript-->
        <script src="/dashboard_assets/vendor/jquery/jquery.min.js"></script>
        <script src="/dashboard_assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
        <!-- Core plugin JavaScript-->
        <script src="/dashboard_assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    
        <!-- Custom scripts for all pages-->
        <script src="/dashboard_assets/js/sb-admin-2.min.js"></script>
    
        <!-- Page level plugins -->
        <script src="/dashboard_assets/vendor/chart.js/Chart.min.js"></script>
    
        <!-- Page level custom scripts -->
        <script src="/dashboard_assets/js/demo/chart-area-demo.js"></script>
        <script src="/dashboard_assets/js/demo/chart-pie-demo.js"></script>

        <script src="/dashboard_assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="/dashboard_assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="/dashboard_assets/js/demo/datatables-demo.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/ckeditor.js"></script>
    <script>
        ClassicEditor.create( document.querySelector( '#writepost' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>

<script src="/chosen/chosen.jquery.min.js"></script>
<script src="/chosen/popper.min.js"></script>
         <script>
            $(document).ready(function() {
                $('.chosen-select').chosen({
                    width: '100%', // Make sure the chosen select takes the full width
                    placeholder_text_single: 'Select an option' // Placeholder text for single select
                });
            });
        </script>
    </body>
    
    </html>