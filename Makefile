LOCAL_IP := $(shell \
	IFACE=$$(route -n get default 2>/dev/null | awk '/interface:/{print $$2}'); \
	IP=$$(ipconfig getifaddr $$IFACE 2>/dev/null); \
	[ -z "$$IP" ] && IP=$$(ipconfig getifaddr en0 2>/dev/null); \
	[ -z "$$IP" ] && IP=$$(ipconfig getifaddr en1 2>/dev/null); \
	[ -z "$$IP" ] && IP=0.0.0.0; \
	echo $$IP)

fresh:
	php artisan migrate:fresh --seed

serve:
	@echo "==> Serving on http://$(LOCAL_IP):8000  (open this on your phone, same Wi-Fi)"
	php -S $(LOCAL_IP):8000 -t public

dev:
	npm run dev -- --host $(LOCAL_IP)

start:
	npm run dev -- --host $(LOCAL_IP) & make serve
