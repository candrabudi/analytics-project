<div class="modal fade" id="tiktokInvoiceModal" tabindex="-1" aria-labelledby="tiktokInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tiktokInvoiceModalLabel">Buat TikTok Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="tiktokInvoiceForm">
                    @csrf
                    <input type="hidden" id="tiktokInvoiceId" name="kol_id">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tiktokRatecardDeal" class="form-label">Ratecard Deal</label>
                            <input type="text" class="form-control" id="tiktokRatecardDeal" name="ratecard_deal"
                                readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="bank_id" class="form-label">Bank</label>
                            <select class="form-select" id="bank_id" name="bank_id" required>
                                <option value="">Pilih Bank</option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Account Details -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="account_name" class="form-label">Nama Akun Bank</label>
                            <input type="text" class="form-control" id="account_name" name="account_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="account_number" class="form-label">Nomor Rekening</label>
                            <input type="text" class="form-control" id="account_number" name="account_number"
                                required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
