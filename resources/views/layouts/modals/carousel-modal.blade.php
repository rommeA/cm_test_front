
<!--BorderLess Modal Modal -->
<div class="modal fade text-center modal-borderless w-100" id="carouselPhotosModal" tabindex="-1" aria-labelledby="carouselModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title white" id="carouselModalLabel">
                    {{ __('Files preview') }}
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <div class="modal-body" id="modal-scans-container">

                <div class="card-carousel">
                    <div class="footer">
                        <a class="btn btn-outline-light btnCarousel" id="prevCarousel" href="#" ripple=""><i class="fa-solid fa-left-long"></i></a>
                        <a class="btn btn-outline-light btnCarousel" id="nextCarousel" href="#" ripple=""><i class="fa-solid fa-right-long"></i></a>
                    </div>
                    <div class="products" id="modal-carousel-inner">

                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary ml-1" data-bs-dismiss="modal">
                    {{ __('Close') }}
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $("#carouselPhotosModal").draggable({
        handle: ".modal-header"
    });
</script>
