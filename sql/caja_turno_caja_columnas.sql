-- =====================================================================
-- CAJA: columnas de apertura y cierre en turno_caja - Panadería M
-- Correr una sola vez en phpMyAdmin (base "original", pestaña SQL).
-- Se puede correr aunque alguna columna ya exista.
-- =====================================================================

-- 1) Si la tabla tiene la columna vieja "fecha", pasa a llamarse "fecha_apertura" (conserva los datos)
ALTER TABLE turno_caja CHANGE COLUMN IF EXISTS fecha fecha_apertura DATETIME NULL;

-- 2) Columnas nuevas (las que ya existan se saltean)
ALTER TABLE turno_caja
    ADD COLUMN IF NOT EXISTS fecha_apertura DATETIME NULL,
    ADD COLUMN IF NOT EXISTS monto_inicial DECIMAL(10,2) NOT NULL DEFAULT 0,
    ADD COLUMN IF NOT EXISTS fecha_cierre DATETIME NULL,
    ADD COLUMN IF NOT EXISTS monto_final DECIMAL(10,2) NOT NULL DEFAULT 0,
    ADD COLUMN IF NOT EXISTS estado VARCHAR(10) NOT NULL DEFAULT 'CERRADO',
    ADD COLUMN IF NOT EXISTS observaciones VARCHAR(255) NULL;
