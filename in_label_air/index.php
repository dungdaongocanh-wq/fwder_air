<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/navbar.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>In Label Hàng Không – KEN LOGISTICS</title>
  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- JsBarcode -->
  <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
  <!-- QRCode.js -->
  <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

  <style>
    /* ============================
       LAYOUT CHUNG
    ============================ */
    body { background: #f0f4f8; }

    .form-section {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(0,0,0,.10);
      padding: 28px 28px 20px;
      margin-bottom: 24px;
    }
    .section-title {
      font-size: 15px;
      font-weight: 700;
      color: #1a3c6e;
      border-left: 4px solid #1a3c6e;
      padding-left: 10px;
      margin-bottom: 16px;
    }

    /* ============================
       HAWB ROWS
    ============================ */
    #hawb-container .hawb-row {
      background: #f8f9fb;
      border: 1px solid #dee2e6;
      border-radius: 8px;
      padding: 12px 14px 8px;
      margin-bottom: 10px;
      position: relative;
    }
    .btn-remove-hawb {
      position: absolute;
      top: 8px;
      right: 10px;
    }

    /* ============================
       LABEL PREVIEW  (90×120 mm)
    ============================ */
    @page { size: 90mm 120mm; margin: 0; }

    .label-wrapper {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 8px;
    }

    .air-label {
      width:  90mm;
      height: 120mm;
      border: 2px solid #222;
      border-radius: 6px;
      font-family: Arial, sans-serif;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      background: #fff;
      /* scale cho dễ xem trên màn hình */
      transform-origin: top left;
    }

    /* --- Phần 1: Airline --- */
    .lbl-airline {
      background: #fff;
      border-bottom: 1px solid #999;
      padding: 3px 6px 2px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .lbl-airline .al-tag {
      font-size: 7pt;
      color: #555;
      align-self: flex-start;
    }
    .lbl-airline .al-name {
      font-size: 13pt;
      font-weight: 900;
      letter-spacing: 1px;
      color: #000;
      line-height: 1.1;
    }

    /* --- Phần 2: Barcode + MAWB số --- */
    .lbl-barcode {
      border-bottom: 1px solid #999;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 3px 4px 2px;
    }
    .lbl-barcode svg, .lbl-barcode canvas {
      max-width: 82mm;
      height: 14mm !important;
    }
    .lbl-barcode .bc-num {
      font-size: 7.5pt;
      letter-spacing: 0.5px;
      margin-top: 1px;
    }

    /* --- Phần 3: Airway Bill No --- */
    .lbl-awb {
      border-bottom: 1px solid #999;
      padding: 3px 8px 3px;
    }
    .lbl-awb .awb-tag {
      font-size: 6.5pt;
      color: #555;
    }
    .lbl-awb .awb-no {
      font-size: 17pt;
      font-weight: 900;
      letter-spacing: 1px;
      line-height: 1.15;
    }

    /* --- Phần 4: Origin / Destination / Pieces --- */
    .lbl-route {
      border-bottom: 1px solid #999;
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
    }
    .lbl-route .rt-cell {
      padding: 3px 6px 4px;
      border-right: 1px solid #bbb;
    }
    .lbl-route .rt-cell:last-child { border-right: none; }
    .lbl-route .rt-tag  { font-size: 6pt; color: #555; }
    .lbl-route .rt-val  { font-size: 16pt; font-weight: 900; line-height: 1.1; }

    /* --- Phần 5: Company + HAWB --- */
    .lbl-company {
      border-bottom: 1px dashed #aaa;
      text-align: center;
      padding: 2px 4px;
      font-size: 8.5pt;
      font-weight: 800;
      letter-spacing: 0.5px;
    }

    .lbl-hawb-row {
      border-bottom: 1px solid #ccc;
      display: grid;
      grid-template-columns: 2fr 1fr 1fr;
    }
    .lbl-hawb-row .hw-cell {
      padding: 2px 5px 3px;
      border-right: 1px solid #ccc;
    }
    .lbl-hawb-row .hw-cell:last-child { border-right: none; }
    .lbl-hawb-row .hw-tag { font-size: 5.5pt; color: #555; }
    .lbl-hawb-row .hw-val { font-size: 9pt; font-weight: 700; }

    .lbl-invoice {
      text-align: center;
      font-size: 6.5pt;
      padding: 2px 4px;
      color: #333;
      font-style: italic;
      margin-top: auto;
    }

    /* ============================
       PRINT
    ============================ */
    @media print {
      body * { visibility: hidden; }
      #print-area, #print-area * { visibility: visible; }
      #print-area {
        position: fixed;
        inset: 0;
        display: flex;
        flex-wrap: wrap;
        gap: 0;
        padding: 0;
        margin: 0;
      }
      .air-label {
        width: 90mm !important;
        height: 120mm !important;
        transform: none !important;
        page-break-after: always;
        border-radius: 0;
      }
    }
  </style>
</head>
<body>

<?php include __DIR__ . '/../includes/navbar.php'; ?>

<div class="container-fluid py-4" style="max-width:960px">

  <h4 class="fw-bold text-primary mb-4">
    <i class="bi bi-tag-fill me-2"></i>In Label Hàng Không
  </h4>

  <!-- ============ FORM ============ -->
  <div class="form-section">
    <div class="section-title">Thông tin vận đơn MAWB</div>
    <div class="row g-3">

      <div class="col-12 col-sm-6 col-md-4">
        <label class="form-label fw-semibold">Tên Airline <span class="text-danger">*</span></label>
        <input type="text" id="inp-airline" class="form-control text-uppercase"
               placeholder="VD: VIETNAM AIRLINES">
      </div>

      <div class="col-12 col-sm-6 col-md-4">
        <label class="form-label fw-semibold">Số MAWB <span class="text-danger">*</span></label>
        <input type="text" id="inp-mawb" class="form-control"
               placeholder="VD: 738-0660 7230">
      </div>

      <div class="col-6 col-md-2">
        <label class="form-label fw-semibold">Origin <span class="text-danger">*</span></label>
        <input type="text" id="inp-origin" class="form-control text-uppercase"
               placeholder="HAN" maxlength="5">
      </div>

      <div class="col-6 col-md-2">
        <label class="form-label fw-semibold">Destination <span class="text-danger">*</span></label>
        <input type="text" id="inp-dest" class="form-control text-uppercase"
               placeholder="NRT" maxlength="5">
      </div>

      <div class="col-6 col-md-2">
        <label class="form-label fw-semibold">Tổng số kiện <span class="text-danger">*</span></label>
        <input type="number" id="inp-pcs" class="form-control" placeholder="5" min="1">
      </div>

      <div class="col-12 col-md-4">
        <label class="form-label fw-semibold">Tên công ty (dòng logo)</label>
        <input type="text" id="inp-company" class="form-control text-uppercase"
               placeholder="KEN LOGISTICS CO.,LTD." value="KEN LOGISTICS CO.,LTD.">
      </div>

    </div>
  </div>

  <!-- ============ HAWB ============ -->
  <div class="form-section">
    <div class="d-flex align-items-center gap-3 mb-2">
      <div class="section-title mb-0">Thông tin HAWB</div>
      <div class="form-check form-switch ms-auto">
        <input class="form-check-input" type="checkbox" id="chk-has-hawb" role="switch">
        <label class="form-check-label fw-semibold" for="chk-has-hawb">Có HAWB</label>
      </div>
    </div>

    <div id="hawb-section" class="d-none">
      <div id="hawb-container"></div>

      <div class="d-flex align-items-center gap-3 mt-2">
        <button class="btn btn-outline-primary btn-sm" onclick="addHawbRow()">
          <i class="bi bi-plus-circle me-1"></i>Thêm HAWB
        </button>
        <span id="hawb-pcs-status" class="small fw-semibold"></span>
      </div>
    </div>
  </div>

  <!-- ============ ERROR ============ -->
  <div id="error-box" class="alert alert-danger d-none" role="alert"></div>

  <!-- ============ BUTTONS ============ -->
  <div class="d-flex gap-3 mb-4">
    <button class="btn btn-primary px-4" onclick="generateLabels()">
      <i class="bi bi-eye me-1"></i>Xem trước
    </button>
    <button class="btn btn-success px-4" id="btn-print" onclick="printLabels()" disabled>
      <i class="bi bi-printer me-1"></i>In Label
    </button>
    <button class="btn btn-secondary" onclick="resetForm()">
      <i class="bi bi-arrow-counterclockwise me-1"></i>Làm lại
    </button>
  </div>

  <!-- ============ PREVIEW ============ -->
  <div id="preview-section" class="d-none">
    <div class="section-title">Xem trước label</div>
    <div id="print-area" class="label-wrapper"></div>
  </div>

</div><!-- /container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ================================================
   HAWB MANAGEMENT
================================================ */
let hawbCount = 0;

document.getElementById('chk-has-hawb').addEventListener('change', function(){
  const sec = document.getElementById('hawb-section');
  if (this.checked) {
    sec.classList.remove('d-none');
    if (hawbCount === 0) addHawbRow();
  } else {
    sec.classList.add('d-none');
  }
});

function addHawbRow() {
  hawbCount++;
  const id = hawbCount;
  const div = document.createElement('div');
  div.className = 'hawb-row';
  div.id = 'hawb-row-' + id;
  div.innerHTML = `
    <button class="btn btn-sm btn-outline-danger btn-remove-hawb" onclick="removeHawbRow(${id})">
      <i class="bi bi-x-lg"></i>
    </button>
    <div class="row g-2">
      <div class="col-12 col-sm-5">
        <label class="form-label small fw-semibold">Số HAWB</label>
        <input type="text" class="form-control form-control-sm hawb-no" placeholder="VD: KINA26051101">
      </div>
      <div class="col-6 col-sm-3">
        <label class="form-label small fw-semibold">H.DST</label>
        <input type="text" class="form-control form-control-sm hawb-dst text-uppercase" placeholder="NRT" maxlength="5">
      </div>
      <div class="col-6 col-sm-3">
        <label class="form-label small fw-semibold">HAWB PCS</label>
        <input type="number" class="form-control form-control-sm hawb-pcs" placeholder="3" min="1"
               onchange="updatePcsStatus()" oninput="updatePcsStatus()">
      </div>
    </div>
  `;
  document.getElementById('hawb-container').appendChild(div);
}

function removeHawbRow(id) {
  const el = document.getElementById('hawb-row-' + id);
  if (el) el.remove();
  updatePcsStatus();
}

function updatePcsStatus() {
  const mawbPcs = parseInt(document.getElementById('inp-pcs').value) || 0;
  const hawbPcsList = [...document.querySelectorAll('.hawb-pcs')].map(x => parseInt(x.value)||0);
  const total = hawbPcsList.reduce((a,b)=>a+b,0);
  const status = document.getElementById('hawb-pcs-status');
  if (mawbPcs === 0) { status.textContent=''; return; }
  if (total === mawbPcs) {
    status.className = 'small fw-semibold text-success';
    status.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i>Tổng kiện HAWB: ${total} / ${mawbPcs} ✔`;
  } else {
    status.className = 'small fw-semibold text-danger';
    status.innerHTML = `<i class="bi bi-exclamation-circle-fill me-1"></i>Tổng kiện HAWB: ${total} / ${mawbPcs} – chưa khớp!`;
  }
}
document.getElementById('inp-pcs').addEventListener('input', updatePcsStatus);

/* ================================================
   COLLECT DATA & VALIDATE
================================================ */
function collectData() {
  const airline  = document.getElementById('inp-airline').value.trim().toUpperCase();
  const mawb     = document.getElementById('inp-mawb').value.trim();
  const origin   = document.getElementById('inp-origin').value.trim().toUpperCase();
  const dest     = document.getElementById('inp-dest').value.trim().toUpperCase();
  const pcs      = parseInt(document.getElementById('inp-pcs').value) || 0;
  const company  = document.getElementById('inp-company').value.trim().toUpperCase();
  const hasHawb  = document.getElementById('chk-has-hawb').checked;

  const errors = [];
  if (!airline) errors.push('Vui lòng nhập tên Airline.');
  if (!mawb)    errors.push('Vui lòng nhập số MAWB.');
  if (!origin)  errors.push('Vui lòng nhập sân bay xuất phát.');
  if (!dest)    errors.push('Vui lòng nhập sân bay đích.');
  if (!pcs)     errors.push('Vui lòng nhập tổng số kiện.');

  const hawbs = [];
  if (hasHawb) {
    const rows = document.querySelectorAll('#hawb-container .hawb-row');
    rows.forEach((row, idx) => {
      const no  = row.querySelector('.hawb-no').value.trim();
      const dst = row.querySelector('.hawb-dst').value.trim().toUpperCase();
      const p   = parseInt(row.querySelector('.hawb-pcs').value) || 0;
      if (!no) errors.push(`HAWB #${idx+1}: Vui lòng nhập số HAWB.`);
      if (!p)  errors.push(`HAWB #${idx+1}: Vui lòng nhập số kiện.`);
      hawbs.push({ no, dst, pcs: p });
    });
    const hawbTotal = hawbs.reduce((a,b)=>a+b.pcs,0);
    if (hawbTotal !== pcs)
      errors.push(`Tổng số kiện HAWB (${hawbTotal}) không bằng số kiện MAWB (${pcs}). Vui lòng kiểm tra lại.`);
  }

  return { airline, mawb, origin, dest, pcs, company, hasHawb, hawbs, errors };
}

/* ================================================
   BUILD ONE LABEL ELEMENT
================================================ */
function buildLabel(data, hawb) {
  const wrap = document.createElement('div');
  wrap.className = 'air-label';

  // 1. Airline
  const sec1 = document.createElement('div');
  sec1.className = 'lbl-airline';
  sec1.innerHTML = `<span class="al-tag">Air Line</span>
                    <span class="al-name">${data.airline}</span>`;
  wrap.appendChild(sec1);

  // 2. Barcode
  const sec2 = document.createElement('div');
  sec2.className = 'lbl-barcode';
  const svgEl = document.createElementNS('http://www.w3.org/2000/svg','svg');
  sec2.appendChild(svgEl);
  const bcNum = document.createElement('div');
  bcNum.className = 'bc-num';
  bcNum.textContent = data.mawb;
  sec2.appendChild(bcNum);
  wrap.appendChild(sec2);

  // 3. AWB
  const sec3 = document.createElement('div');
  sec3.className = 'lbl-awb';
  sec3.innerHTML = `<div class="awb-tag">Air Way Bill No</div>
                    <div class="awb-no">${data.mawb}</div>`;
  wrap.appendChild(sec3);

  // 4. Route
  const sec4 = document.createElement('div');
  sec4.className = 'lbl-route';
  sec4.innerHTML = `
    <div class="rt-cell"><div class="rt-tag">Origin</div><div class="rt-val">${data.origin}</div></div>
    <div class="rt-cell"><div class="rt-tag">Destination</div><div class="rt-val">${data.dest}</div></div>
    <div class="rt-cell"><div class="rt-tag">Total No.of Pieces</div><div class="rt-val">${data.pcs}</div></div>
  `;
  wrap.appendChild(sec4);

  // 5. Company
  if (data.company) {
    const sec5 = document.createElement('div');
    sec5.className = 'lbl-company';
    sec5.textContent = data.company;
    wrap.appendChild(sec5);
  }

  // 6. HAWB info (nếu có)
  if (hawb) {
    const sec6 = document.createElement('div');
    sec6.className = 'lbl-hawb-row';
    sec6.innerHTML = `
      <div class="hw-cell"><div class="hw-tag">HOUSE AIRWAYBILL NO</div><div class="hw-val">${hawb.no}</div></div>
      <div class="hw-cell"><div class="hw-tag">H.DST</div><div class="hw-val">${hawb.dst||data.dest}</div></div>
      <div class="hw-cell"><div class="hw-tag">HAWB PCS</div><div class="hw-val">${hawb.pcs}</div></div>
    `;
    wrap.appendChild(sec6);
  }

  // Render barcode SAU KHI gắn vào DOM (cần element mounted)
  setTimeout(()=>{
    try {
      JsBarcode(svgEl, data.mawb.replace(/[^0-9A-Za-z\-]/g,''), {
        format: 'CODE128',
        displayValue: false,
        height: 40,
        width: 1.4,
        margin: 4
      });
    } catch(e) { svgEl.remove(); }
  }, 0);

  return wrap;
}

/* ================================================
   GENERATE LABELS
================================================ */
function generateLabels() {
  const data = collectData();
  const errBox = document.getElementById('error-box');

  if (data.errors.length) {
    errBox.innerHTML = '<b><i class="bi bi-exclamation-triangle-fill me-2"></i>Lỗi:</b><ul class="mb-0 mt-1">' +
                       data.errors.map(e=>`<li>${e}</li>`).join('') + '</ul>';
    errBox.classList.remove('d-none');
    document.getElementById('btn-print').disabled = true;
    return;
  }
  errBox.classList.add('d-none');

  const area = document.getElementById('print-area');
  area.innerHTML = '';

  if (data.hasHawb && data.hawbs.length) {
    // Mỗi HAWB một label riêng
    data.hawbs.forEach(h => area.appendChild(buildLabel(data, h)));
  } else {
    // Chỉ label MAWB
    area.appendChild(buildLabel(data, null));
  }

  document.getElementById('preview-section').classList.remove('d-none');
  document.getElementById('btn-print').disabled = false;

  // Scroll xuống preview
  document.getElementById('preview-section').scrollIntoView({ behavior:'smooth' });
}

/* ================================================
   PRINT
================================================ */
function printLabels() { window.print(); }

/* ================================================
   RESET
================================================ */
function resetForm() {
  ['inp-airline','inp-mawb','inp-origin','inp-dest','inp-pcs'].forEach(id=>{
    document.getElementById(id).value='';
  });
  document.getElementById('inp-company').value='KEN LOGISTICS CO.,LTD.';
  document.getElementById('chk-has-hawb').checked=false;
  document.getElementById('hawb-section').classList.add('d-none');
  document.getElementById('hawb-container').innerHTML='';
  hawbCount=0;
  document.getElementById('error-box').classList.add('d-none');
  document.getElementById('preview-section').classList.add('d-none');
  document.getElementById('print-area').innerHTML='';
  document.getElementById('btn-print').disabled=true;
}
</script>
</body>
</html>
