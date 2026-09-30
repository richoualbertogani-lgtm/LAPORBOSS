/**
 * Perilaku form "Buat Aspirasi":
 *  1. Pratinjau lampiran foto beserta tombol Ganti dan Hapus.
 *  2. Draf tersimpan otomatis di peramban (localStorage).
 */

// Kunci penyimpanan draf dan batas ukuran foto (2 MB, sama dengan validasi server).
const KUNCI_DRAF = 'laporboss_draf_aspirasi';
const BATAS_UKURAN = 2 * 1024 * 1024;

// Kolom teks yang disimpan sebagai draf.
const KOLOM_DRAF = ['nis', 'nama', 'rombel', 'id_kategori', 'judul', 'isi_aspirasi'];

/** Ubah ukuran byte menjadi teks, contoh: "1,8 MB". */
function formatUkuran(byte) {
    if (byte >= 1024 * 1024) {
        return (byte / 1024 / 1024).toFixed(1).replace('.', ',') + ' MB';
    }
    return Math.max(1, Math.round(byte / 1024)) + ' KB';
}

/** Hapus draf yang tersimpan. */
function hapusDraf() {
    try {
        localStorage.removeItem(KUNCI_DRAF);
    } catch (e) {
        // Penyimpanan peramban tidak tersedia; abaikan.
    }
}

/** Atur pratinjau lampiran foto. */
function aturLampiran() {
    const input = document.getElementById('lampiran');
    const kosong = document.getElementById('lampiran-kosong');
    const terpilih = document.getElementById('lampiran-terpilih');
    const thumb = document.getElementById('lampiran-thumb');
    const pesanError = document.getElementById('lampiran-error');

    // Tampilkan kondisi "belum ada foto" atau "foto terpilih".
    const tampilkan = (file) => {
        kosong.hidden = Boolean(file);
        terpilih.hidden = !file;

        if (!file) {
            thumb.removeAttribute('src');
            return;
        }

        thumb.src = URL.createObjectURL(file);
        document.getElementById('lampiran-nama').textContent = file.name;
        document.getElementById('lampiran-ukuran').textContent =
            formatUkuran(file.size) + ' · ' + (file.type.split('/')[1] || '').toUpperCase();
    };

    document.getElementById('lampiran-pilih').addEventListener('click', () => input.click());
    document.getElementById('lampiran-ganti').addEventListener('click', () => input.click());

    // Hapus foto: kosongkan input dan kembalikan tampilan awal.
    document.getElementById('lampiran-hapus').addEventListener('click', () => {
        input.value = '';
        pesanError.textContent = '';
        tampilkan(null);
    });

    input.addEventListener('change', () => {
        const file = input.files[0];
        pesanError.textContent = '';

        // Tolak foto yang terlalu besar sebelum dikirim ke server.
        if (file && file.size > BATAS_UKURAN) {
            pesanError.textContent = 'Ukuran foto maksimal 2 MB.';
            input.value = '';
            tampilkan(null);
            return;
        }

        tampilkan(file || null);
    });
}

/** Atur penyimpanan dan pemulihan draf. */
function aturDraf(form) {
    const status = document.getElementById('status-draf');
    let penunda = null;

    // Pulihkan draf hanya pada kolom yang masih kosong (tidak menimpa old() dari server).
    try {
        const draf = JSON.parse(localStorage.getItem(KUNCI_DRAF) || '{}');
        KOLOM_DRAF.forEach((nama) => {
            const kolom = form.elements[nama];
            if (kolom && !kolom.value && draf[nama]) {
                kolom.value = draf[nama];
            }
        });
    } catch (e) {
        // Draf rusak atau penyimpanan tidak tersedia; abaikan.
    }

    // Simpan draf 500 ms setelah pengguna berhenti mengetik.
    form.addEventListener('input', () => {
        clearTimeout(penunda);
        penunda = setTimeout(() => {
            const draf = {};
            KOLOM_DRAF.forEach((nama) => {
                draf[nama] = form.elements[nama].value;
            });

            try {
                localStorage.setItem(KUNCI_DRAF, JSON.stringify(draf));
                const jam = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                status.textContent = 'Draf tersimpan pukul ' + jam;
            } catch (e) {
                status.textContent = 'Draf tidak dapat disimpan';
            }
        }, 500);
    });

    // Tombol Batal membuang draf.
    document.getElementById('tombol-batal').addEventListener('click', hapusDraf);
}

// Jalankan bila halaman form tersedia.
const form = document.getElementById('form-aspirasi');
if (form) {
    aturLampiran();
    aturDraf(form);
}

// Halaman lacak menandai aspirasi sudah terkirim, jadi draf lama dibuang.
if (document.querySelector('[data-hapus-draf]')) {
    hapusDraf();
}
