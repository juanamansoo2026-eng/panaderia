-- =====================================================================
-- CAJA CON APERTURA Y CIERRE - Panadería M
-- Correr una sola vez en phpMyAdmin (base "original", pestaña SQL)
-- =====================================================================

-- 1) Las ventas quedan asociadas al turno de caja abierto
ALTER TABLE ventas ADD COLUMN IF NOT EXISTS turno_id INT NULL AFTER empleado_id;
ALTER TABLE ventas ADD INDEX IF NOT EXISTS idx_ventas_turno (turno_id);

-- 2) (OPCIONAL) Cerrar las cajas viejas que quedaron en ABIERTO.
--    Hoy todas figuran abiertas porque nunca se pudieron cerrar, y el sistema
--    no deja abrir una caja nueva mientras haya una abierta.
--    Quedan como CERRADO sin arqueo (no se toca nada más).
-- UPDATE turno_caja SET estado = 'CERRADO' WHERE estado = 'ABIERTO';
