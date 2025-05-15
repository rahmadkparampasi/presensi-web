<script src="/vendors/script/pdfjs-2.6.347-dist/build/pdf.js"></script>

<div class="modal fade bd-example-modal-lg" id="modalViewPdf" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalViewPdfLabel">LIHAT BERKAS </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="width: 90%; height: auto;">
                    <div class="row">
                        <button class="btn btn-primary m-3" id="prev"> SEBELUMNYA</button>
                        <button class="btn btn-info m-3" id="next">SELANJUTNYA</button>
                        &nbsp; &nbsp;
                        <span>Halaman: <span id="page_num"></span> / <span id="page_count"></span></span>
                    </div>
                    <canvas id="cvsPdf" style="width: 90%;"></canvas>
                </div>


                <script>
                var url = '';

                function changeUrl(params, labelPdf) {
                    document.getElementById("modalViewPdfLabel").innerHTML = labelPdf;
                    document.getElementById("modalViewPdfLabelDownload").setAttribute('href', params);
                    url = params;
                    // If absolute URL from the remote server is provided, configure the CORS
                    // header on that server.

                    // Loaded via <script> tag, create shortcut to access PDF.js exports.
                    var pdfjsLib = window['pdfjs-dist/build/pdf'];

                    // The workerSrc property shall be specified.
                    pdfjsLib.GlobalWorkerOptions.workerSrc = '{{url('vendors/script/pdfjs-2.6.347-dist/build/pdf.worker.js')}}';

                    var pdfDoc = null,
                        pageNum = 1,
                        pageRendering = false,
                        pageNumPending = null,
                        scale = 0.8,
                        canvas = document.getElementById('cvsPdf'),
                        ctx = canvas.getContext('2d');

                    /**
                     * Get page info from document, resize canvas accordingly, and render page.
                     * @param num Page number.
                     */
                    function renderPage(num) {
                        pageRendering = true;
                        // Using promise to fetch the page
                        pdfDoc.getPage(num).then(function(page) {
                            var viewport = page.getViewport({
                                scale: scale
                            });
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;

                            // Render PDF page into canvas context
                            var renderContext = {
                                canvasContext: ctx,
                                viewport: viewport
                            };
                            var renderTask = page.render(renderContext);

                            // Wait for rendering to finish
                            renderTask.promise.then(function() {
                                pageRendering = false;
                                if (pageNumPending !== null) {
                                    // New page rendering is pending
                                    renderPage(pageNumPending);
                                    pageNumPending = null;
                                }
                            });
                        });

                        // Update page counters
                        document.getElementById('page_num').textContent = num;
                    }

                    /**
                     * If another page rendering in progress, waits until the rendering is
                     * finised. Otherwise, executes rendering immediately.
                     */
                    function queueRenderPage(num) {
                        if (pageRendering) {
                            pageNumPending = num;
                        } else {
                            renderPage(num);
                        }
                    }

                    /**
                     * Displays previous page.
                     */
                    function onPrevPage() {
                        if (pageNum <= 1) {
                            return;
                        }
                        pageNum--;
                        queueRenderPage(pageNum);
                    }
                    document.getElementById('prev').addEventListener('click', onPrevPage);

                    /**
                     * Displays next page.
                     */
                    function onNextPage() {
                        if (pageNum >= pdfDoc.numPages) {
                            return;
                        }
                        pageNum++;
                        queueRenderPage(pageNum);
                    }
                    document.getElementById('next').addEventListener('click', onNextPage);

                    /**
                     * Asynchronously downloads PDF.
                     */
                    pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
                        pdfDoc = pdfDoc_;
                        document.getElementById('page_count').textContent = pdfDoc.numPages;

                        // Initial/first page rendering
                        renderPage(pageNum);
                    });
                }
                </script>
            </div>
            <div class="modal-footer">
                <div class="item form-group">
                    <a href="#" id="modalViewPdfLabelDownload" target="_blank" class="btn btn-info"><i class="fa fa-download"></i> UNDUH</a>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> TUTUP</button>
                </div>
            </div>
        </div>
    </div>
</div>