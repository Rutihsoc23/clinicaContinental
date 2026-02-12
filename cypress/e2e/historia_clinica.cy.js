Cypress.on('uncaught:exception', (err, runnable) => {
    if (err.message.includes("reading 'document'") || err.message.includes("null")) {
        return false;
    }
    return true;
});

describe('SIMICRA - Lógica de Reportes: Pendientes vs Confirmadas', () => {

    const idUnico = Date.now().toString().slice(-4);
    const fechaEliminar = '2026-11-01'; // Cita que se borrará
    const fechaPersistir = '2026-11-02'; // Cita que se quedará

    beforeEach(() => {
        cy.visit('http://127.0.0.1:8000/login');
        cy.get('input[name="email"]').type('admin@clinica.com');
        cy.get('input[name="password"]').type('admin123');
        cy.get('button[type="submit"]').last().click();
    });

    // ESCENARIO 1: SI ESTÁ PENDIENTE -> SE ELIMINA (INASISTENCIA)
    it('RF09 - Inasistencia: Eliminar cita si quedó como Pendiente', () => {
        // 1. Agendamos la cita como Pendiente
        cy.visit('/citas/create');
        cy.get('select[name="paciente_id"]').select(1, { force: true });
        cy.get('select[name="doctor_id"]').select(1, { force: true });
        cy.get('input[name="fecha_cita"]').type(fechaEliminar);
        cy.get('input[name="hora_cita"]').type('08:00');
        
        cy.get('select[name="estado_id"]').select('Pendiente', { force: true });
        cy.get('button[type="submit"]').last().click();

        // 2. Simulamos la eliminación por no presentarse
        cy.visit('/citas');
        cy.contains('tr', fechaEliminar).within(() => {
            // Buscamos el botón de Cancelar/Eliminar
            cy.get('button, a').filter(':contains("Cancelar"), :contains("Eliminar")').click({ force: true });
        });

        // 3. Verificación: Ya no debe estar en la tabla
        cy.get('table').should('not.contain', fechaEliminar);
    });

    // ESCENARIO 2: SI ESTÁ CONFIRMADA -> SE QUEDA EN EL REPORTE
    it('RF10 - Asistencia: Mantener cita si está Confirmada', () => {
        // 1. Agendamos la cita como Confirmada
        cy.visit('/citas/create');
        cy.get('select[name="paciente_id"]').select(1, { force: true });
        cy.get('select[name="doctor_id"]').select(1, { force: true });
        cy.get('input[name="fecha_cita"]').type(fechaPersistir);
        cy.get('input[name="hora_cita"]').type('10:00');
        
        // Estado: Confirmada (Esta no se borra)
        cy.get('select[name="estado_id"] option').then($options => {
            const op = [...$options].find(o => o.text.trim().includes('Confirmad'));
            if (op) cy.get('select[name="estado_id"]').select(op.value, { force: true });
        });
        cy.get('button[type="submit"]').last().click();

        // 2. Verificación de permanencia
        cy.visit('/citas');
        cy.contains('tr', fechaPersistir).should('be.visible');
        cy.contains('tr', fechaPersistir).should('contain', 'Confirmada');
        
        // Aseguramos que NO se haya eliminado por error
        cy.get('table').should('contain', fechaPersistir);
    });
});