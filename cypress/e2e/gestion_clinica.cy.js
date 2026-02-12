Cypress.on('uncaught:exception', (err, runnable) => {
    if (err.message.includes("reading 'document'") || err.message.includes("null")) {
        return false;
    }
    return true;
});

describe('Sistema de Gestión Clínica Continental - SIMICRA', () => {

    const idUnico = Date.now().toString().slice(-4); 
    const dniNuevoPaciente = `45${idUnico}11`; 
    const emailNuevoPaciente = `santi_${idUnico}@mail.com`;
    const dniNuevoDoctor = `55${idUnico}22`;
    const nombreEditado = "Editado_" + idUnico;

    beforeEach(() => {
        cy.visit('http://127.0.0.1:8000/login'); 
        cy.get('input[name="email"]').type('admin@clinica.com');
        cy.get('input[name="password"]').type('admin123');
        cy.get('button[type="submit"]').last().click(); 
        cy.get('body').should('be.visible');
    });

    // --- PARTE A: PACIENTES ---
    it('RF01 - Gestión de Pacientes Únicos y Edición', () => {
        cy.visit('/pacientes/create');
        cy.get('input[name="nombre_paciente"]').type('Santi');
        cy.get('input[name="apellido_paterno_paciente"]').type('Quispe');
        cy.get('input[name="apellido_materno_paciente"]').type('Mamani');
        cy.get('input[name="dni_paciente"]').type(dniNuevoPaciente);
        cy.get('input[name="email_paciente"]').type(emailNuevoPaciente);
        cy.get('input[name="telefono_paciente"]').type('987654321');
        cy.get('button[type="submit"]').last().click();

        // Validación de Duplicado (Email)
        cy.visit('/pacientes/create');
        cy.get('input[name="dni_paciente"]').type(`99${idUnico}00`); 
        cy.get('input[name="email_paciente"]').type(emailNuevoPaciente); 
        cy.get('button[type="submit"]').last().click();
        cy.contains(/correo|email|ya existe|registrado/i).should('be.visible');

        // Edición (FIX: Selector por clase para evitar error de texto)
        cy.visit('/pacientes');
        cy.contains('tr', dniNuevoPaciente).find('.btn-warning').click({force: true});
        cy.get('input[name="nombre_paciente"]').clear().type(nombreEditado);
        cy.get('button[type="submit"]').last().click();
        cy.contains(nombreEditado).should('be.visible');
    });

    // --- PARTE B: DOCTORES ---
    it('RF02 - Registro de Doctor y Edición de Especialidad', () => {
        cy.visit('/doctores/create');
        cy.get('input[name="nombre_doctor"]').type('Gregory');
        cy.get('input[name="apellido_paterno_doctor"]').type('House');
        cy.get('input[name="apellido_materno_doctor"]').type('Mendoza');
        cy.get('input[name="dni_doctor"]').type(dniNuevoDoctor);
        cy.get('input[name="cmp_doctor"]').type(`CMP${idUnico}`);

        cy.get('select[name="especialidad_id"] option').then($options => {
            const op = [...$options].find(o => o.text.trim() === 'Cardiología');
            if (op) cy.get('select[name="especialidad_id"]').select(op.value, { force: true });
        });

        cy.get('input[type="checkbox"]').first().check(); 
        cy.get('input[name="hora_inicio"]').type('08:00');
        cy.get('input[name="hora_fin"]').type('12:00');
        cy.get('button[type="submit"]').last().click();

        // Edición (FIX: Selector por clase para evitar error de texto)
        cy.visit('/doctores');
        cy.contains('tr', dniNuevoDoctor).find('.btn-warning').click({force: true});
        cy.get('input[name="nombre_doctor"]').clear().type(nombreEditado);
        cy.get('button[type="submit"]').last().click();
        cy.contains(nombreEditado).should('be.visible');
    });

    // --- PARTE C: CITAS ---
    it('RF04 - Agendamiento de Cita', () => {
        cy.visit('/citas/create');
        cy.wait(1000);

        // 1. Selección de Paciente (Buscamos a Carlos)
        cy.get('select[name="paciente_id"] option').then($options => {
            const op = [...$options].find(o => o.text.includes('Carlos'));
            if (op) cy.get('select[name="paciente_id"]').select(op.value, { force: true });
        });

        // 2. NUEVO: Selección de Especialista/Doctor (Maglioni)
        // Este bloque es el que faltaba en tu código anterior
        cy.get('select[name="doctor_id"] option').then($options => {
            const op = [...$options].find(o => o.text.includes('Maglioni'));
            if (op) cy.get('select[name="doctor_id"]').select(op.value, { force: true });
        });

        cy.get('input[name="fecha_cita"]').type('2026-05-20');
        cy.get('input[name="hora_cita"]').type('10:30');

        // 3. Selección de Estado
        cy.get('select[name="estado_id"] option').then($options => {
            const op = [...$options].find(o => o.text.trim() === 'Confirmada');
            if (op) cy.get('select[name="estado_id"]').select(op.value, { force: true });
        });

        // Enviamos el formulario usando el último botón (para evitar el logout)
        cy.get('button[type="submit"]').last().click();

        // Verificación final
        cy.url().should('include', '/citas');
        cy.get('table').should('contain', 'Carlos');
    });
});