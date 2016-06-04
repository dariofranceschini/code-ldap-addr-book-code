# ------------------------------------

PACKAGE_NAME=LDAP Address Book
PACKAGE_COPYRIGHT=James Turner and other contributors

LANGUAGES=en es fr

# ------------------------------------

MAIN_PO=main_${BUILD_LANGUAGE}.po
MAIN_MO=${BUILD_LANGUAGE}/LC_MESSAGES/main.mo

SCHEMAS=$(shell find ../schema -name *.php | sed "s/^..\/schema\///;s/.php//;s/\//_/g")

SCHEMA_POT=$(foreach schema,${SCHEMAS},${schema}_schema.pot)
SCHEMA_PO=$(foreach schema,${SCHEMAS},${schema}_schema_${BUILD_LANGUAGE}.po)
SCHEMA_MO=$(foreach schema,${SCHEMAS},${BUILD_LANGUAGE}/LC_MESSAGES/${schema}_schema.mo)

.SECONDARY: main.pot ${MAIN_PO} ${SCHEMA_POT} ${SCHEMA_PO}

# ------------------------------------

all:
	@for BUILD_LANGUAGE in ${LANGUAGES}; do \
		echo; echo Updating translation: $${BUILD_LANGUAGE}; echo; \
		${MAKE} --no-print-directory translate BUILD_LANGUAGE=$${BUILD_LANGUAGE}; \
	done

clean:
	rm -rf *.pot ${LANGUAGES}

# ------------------------------------

translate: ${MAIN_MO} ${SCHEMA_MO}

main.pot: $(wildcard ../*.php)
	ls ../*.php | xargs xgettext -o main.pot \
		--from-code UTF-8 \
		--package-name="${PACKAGE_NAME}" \
		--copyright-holder="${PACKAGE_COPYRIGHT}"
	sed -i -e"s/CHARSET/UTF-8/" $@

%_schema.pot: ../schema/%.php
	xgettext -o $@ \
		--from-code UTF-8 \
                --package-name="${PACKAGE_NAME}" \
                --copyright-holder="${PACKAGE_COPYRIGHT}" $<
	if [ ! -f $@ ]; then touch $@; fi
	sed -i -e"s/CHARSET/UTF-8/" $@

# Generate pot files for component schemas in "system" subdirectory

system_%_schema.pot: ../schema/system/%.php
	xgettext -o $@ \
		--from-code UTF-8 \
                --package-name="${PACKAGE_NAME}" \
                --copyright-holder="${PACKAGE_COPYRIGHT}" $<
	if [ ! -f $@ ]; then touch $@; fi
	sed -i -e"s/CHARSET/UTF-8/" $@

# Generate pot files for component schemas in "microsoft" subdirectory

microsoft_%_schema.pot: ../schema/microsoft/%.php
	xgettext -o $@ \
		--from-code UTF-8 \
                --package-name="${PACKAGE_NAME}" \
                --copyright-holder="${PACKAGE_COPYRIGHT}" $<
	if [ ! -f $@ ]; then touch $@; fi
	sed -i -e"s/CHARSET/UTF-8/" $@

# Generate pot files for component schemas in "novell" subdirectory

novell_%_schema.pot: ../schema/novell/%.php
	xgettext -o $@ \
		--from-code UTF-8 \
                --package-name="${PACKAGE_NAME}" \
                --copyright-holder="${PACKAGE_COPYRIGHT}" $<
	if [ ! -f $@ ]; then touch $@; fi
	sed -i -e"s/CHARSET/UTF-8/" $@

%_${BUILD_LANGUAGE}.po: %.pot
	@if [ -f $@ ]; then \
		echo Updating $@; \
		msgmerge -qU $@ $< --backup none; \
		touch $@; \
	else \
		if [ "${BUILD_LANGUAGE}" = "en" ]; then \
			msgen --lang=en $< --output-file=$@; \
			if [ ! -f $@ ]; then touch $@; fi \
		else \
			msginit --no-translator --locale=${BUILD_LANGUAGE} --input=$< --output-file=$@; \
		fi \
	fi

${BUILD_LANGUAGE}/LC_MESSAGES/%.mo: %_${BUILD_LANGUAGE}.po
	@mkdir -p `dirname $@`
	@echo -n "$<: "
	@rm -f $@
	@msgfmt -v $< -o $@
	@if [ ! -f $@ ]; then touch $@; fi
	@chmod g+r $@
