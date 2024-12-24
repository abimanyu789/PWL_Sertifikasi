describe('Testing Page Admin', () => {
  const baseUrl = 'http://127.0.0.1:8000';

  beforeEach(() => {
    cy.session('admin', () => {
      cy.visit(`${baseUrl}/login`);
      cy.get('input[name="username"]').type('admin'); 
      cy.get('input[name="password"]').type('123456'); // Ganti dengan password tes
      cy.get('button[type="submit"]').click();

      cy.get('.swal2-confirm').should('be.visible').click();
      cy.url().should('eq', `${baseUrl}/`);
    });
  });

  it('Should display profile page', () => {
    cy.visit(`${baseUrl}/profile`);
  });

  it('Should display user page', () => {
    cy.visit(`${baseUrl}/user`);
  });

  it('Should display level page', () => {
    cy.visit(`${baseUrl}/level`);
  });

  it('Should display pelatihan page', () => {
    cy.visit(`${baseUrl}/pelatihan`);
  });

  it('Should display sertifikasi page', () => {
    cy.visit(`${baseUrl}/sertifikasi`);
  });

  it('Should display jenis page', () => {
    cy.visit(`${baseUrl}/jenis`);
  });

  it('Should display bidang page', () => {
    cy.visit(`${baseUrl}/bidang`);
  });

  it('Should display mata kuliah page', () => {
    cy.visit(`${baseUrl}/matkul`);
  });

  it('Should display vendor page', () => {
    cy.visit(`${baseUrl}/vendor`);
  });

  it('Should display periode page', () => {
    cy.visit(`${baseUrl}/periode`);
  });

  it('Should display draft surat tugas', () => {
    cy.visit(`${baseUrl}/surat_tugas`);
  });
});

describe('Testing Page Dosen', () => {
  const baseUrl = 'http://127.0.0.1:8000';

  beforeEach(() => {
    cy.session('dosen', () => {
      cy.visit(`${baseUrl}/login`);
      cy.get('input[name="username"]').type('iyzidann'); 
      cy.get('input[name="password"]').type('123456'); // Ganti dengan password tes
      cy.get('button[type="submit"]').click();

      cy.get('.swal2-confirm').should('be.visible').click();
      cy.url().should('eq', `${baseUrl}/`);
    });
  });

  it('Should display profile page', () => {
    cy.visit(`${baseUrl}/profile`);
  });

  it('Should display upload sertifikasi page', () => {
    cy.visit(`${baseUrl}/upload_sertifikasi`);
  });

  it('Should display draft upload pelatihan page', () => {
    cy.visit(`${baseUrl}/upload_pelatihan`);
  });

  it('Should display draft surat tugas page', () => {
    cy.visit(`${baseUrl}/surat_tugas`);
  });
});

describe('Testing Page Pimpinan', () => {
  const baseUrl = 'http://127.0.0.1:8000';

  beforeEach(() => {
    cy.session('pimpinan', () => {
      cy.visit(`${baseUrl}/login`);
      cy.get('input[name="username"]').type('pimpinan'); 
      cy.get('input[name="password"]').type('123456'); // Ganti dengan password tes
      cy.get('button[type="submit"]').click();

      cy.get('.swal2-confirm').should('be.visible').click();
      cy.url().should('eq', `${baseUrl}/`);
    });
  });

  it('Should display profile page', () => {
    cy.visit(`${baseUrl}/profile`);
  });

  it('Should display monitoring page', () => {
    cy.visit(`${baseUrl}/view_dosen`);
  });

  it('Should display draft validasi pengajuan page', () => {
    cy.visit(`${baseUrl}/acc_daftar`);
  });
});
