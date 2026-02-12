describe('SIMICRA - Seguridad Completa: Bloqueo y Desbloqueo', () => {

    beforeEach(() => {
        // Limpiamos cookies y caché para empezar limpio
        cy.clearCookies();
        cy.clearLocalStorage();
        cy.visit('http://127.0.0.1:8000/login');
    });

    it('RF-SEG-01: Bloqueo tras 3 intentos, espera de 30s y Login Exitoso', () => {
        const email = 'admin@clinica.com';
        const passMal = 'CLAVE_ERROR';
        const passBien = 'admin123'; // La contraseña correcta de tu admin

        // --- FASE 1: PROVOCAR EL BLOQUEO (3 INTENTOS) ---
        // Hacemos un bucle de 2 intentos normales primero
        for (let i = 1; i <= 2; i++) {
            cy.get('input[name="email"]').clear().type(email);
            cy.get('input[name="password"]').clear().type(passMal);
            cy.get('button[type="submit"]').click();
            cy.contains('no coinciden').should('be.visible'); // Verifica error normal
        }

        // El intento #3 (El que bloquea)
        cy.log('🔒 Ejecutando Intento #3 para bloquear...');
        cy.get('input[name="email"]').clear().type(email);
        cy.get('input[name="password"]').clear().type(passMal);
        cy.get('button[type="submit"]').click();

        // Verificamos que se bloqueó (Alerta roja visible)
        cy.get('#countdown-alert').should('be.visible');
        cy.get('input[name="email"]').should('be.disabled'); // Inputs grises

        // --- FASE 2: LA ESPERA REAL (32 SEGUNDOS) ---
        cy.log('⏳ Esperando 32 segundos a que expire el castigo...');
        
        // ¡IMPORTANTE! El test se quedará "congelado" aquí 32 segundos. Es normal.
        cy.wait(32000); 

        // --- FASE 3: VERIFICAR DESBLOQUEO AUTOMÁTICO ---
        cy.log('🔓 Verificando que el sistema se desbloqueó...');
        
        // 1. La alerta roja debe haber desaparecido
        cy.get('#countdown-alert').should('not.be.visible');

        // 2. Los inputs deben estar habilitados de nuevo
        cy.get('input[name="email"]').should('not.be.disabled');
        cy.get('input[name="password"]').should('not.be.disabled');
        cy.get('button[type="submit"]').should('not.be.disabled');

        // --- FASE 4: LOGIN EXITOSO ---
        cy.log('✅ Intentando ingresar con contraseña correcta...');
        
        cy.get('input[name="email"]').clear().type(email);
        cy.get('input[name="password"]').clear().type(passBien);
        cy.get('button[type="submit"]').click();

        // Validamos que entramos al Dashboard (URL ya no es login)
        cy.url().should('not.include', '/login');
        
        // Validamos elementos del home (ajusta según tu dashboard)
        cy.get('body').should('contain', 'CLÍNICA CONTINENTAL');
    });

});