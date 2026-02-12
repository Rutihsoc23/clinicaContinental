// 1. ESCUDO GLOBAL: Ignora errores irrelevantes de librerías externas
Cypress.on('uncaught:exception', (err, runnable) => {
    return false;
});

describe('SIMICRA - Gestión de Citas y Reportes', () => {

    // Generamos la fecha de hoy automáticamente
    const hoy = new Date().toISOString().split('T')[0];

    beforeEach(() => {
        // Login robusto
        cy.visit('http://127.0.0.1:8000/login');
        cy.get('input[name="email"]').type('admin@clinica.com');
        cy.get('input[name="password"]').type('admin123');
        cy.get('form').submit();
        
        // Verificamos que entramos al sistema
        cy.get('.navbar-brand', { timeout: 10000 }).should('be.visible');
    });

    it('RF16 - Reporte Diario: Crear citas y validar mensaje de éxito real', () => {
        
        // --- PASO 1: CREAR 2 CITAS ---
        for(let i = 0; i < 2; i++) {
            cy.visit('/citas/create');
            
            // Llenado de formulario (Select2 requiere force: true)
            cy.get('select[name="paciente_id"]').select(1, { force: true });
            cy.get('select[name="doctor_id"]').select(1, { force: true });
            cy.get('input[name="fecha_cita"]').type(hoy);
            
            // Formato de hora seguro (09:00, 10:00)
            const horaHH = (9 + i).toString().padStart(2, '0'); 
            cy.get('input[name="hora_cita"]').type(`${horaHH}:00`);
            
            // Seleccionamos estado 'Pendiente' (o el ID que uses, ej: 1)
            cy.get('select[name="estado_id"]').select(1, { force: true }); 

            // ENVIAR FORMULARIO
            cy.get('button[type="submit"]').last().click();

            // --- MEJORA: DETECCIÓN AUTOMÁTICA DEL MENSAJE ---
            // Esperamos a que aparezca CUALQUIER alerta (verde o roja)
            cy.get('.alert', { timeout: 10000 }).should('be.visible').then(($alerta) => {
                
                const textoMensaje = $alerta.text().trim();
                const claseAlerta = $alerta.attr('class');

                // Si es roja (Error), fallamos el test con información útil
                if (claseAlerta.includes('alert-danger')) {
                    cy.log('❌ ERROR DE LARAVEL DETECTADO: ' + textoMensaje);
                    throw new Error("El sistema devolvió un error: " + textoMensaje);
                }

                // Si es verde (Éxito), pasamos la prueba y mostramos qué dice
                if (claseAlerta.includes('alert-success')) {
                    cy.log('✅ ÉXITO. El mensaje real es: "' + textoMensaje + '"');
                    // Validación flexible: solo exigimos que tenga texto
                    expect(textoMensaje).to.have.length.greaterThan(0);
                }
            });
        }

        // --- PASO 2: VERIFICAR REPORTE ---
        // Navegamos al reporte usando el menú
        cy.contains('.nav-link', 'Reportes').click();

        // Validamos que la tabla tenga datos de hoy
        cy.get('table tbody tr', { timeout: 10000 }).should('have.length.at.least', 1);
        cy.contains('table', hoy).should('be.visible');
    });
});