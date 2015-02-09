%global composer_vendor  fkooman
%global composer_project rest-plugin-simplesaml

%global github_owner     fkooman
%global github_name      php-lib-rest-plugin-simplesaml

Name:       php-%{composer_vendor}-%{composer_project}
Version:    0.1.1
Release:    1%{?dist}
Summary:    simpleSAMLphp (SAML) Authentication plugin for fkooman/rest

Group:      System Environment/Libraries
License:    ASL 2.0
URL:        https://github.com/%{github_owner}/%{github_name}
Source0:    https://github.com/%{github_owner}/%{github_name}/archive/%{version}.tar.gz
BuildArch:  noarch

Provides:   php-composer(%{composer_vendor}/%{composer_project}) = %{version}

Requires:   php >= 5.3.3

Requires:   php-composer(fkooman/rest) >= 0.6.7
Requires:   php-composer(fkooman/rest) < 0.7.0

%description
Library written in PHP to make it easy to develop REST applications.

%prep
%setup -qn %{github_name}-%{version}

%build

%install
mkdir -p ${RPM_BUILD_ROOT}%{_datadir}/php
cp -pr src/* ${RPM_BUILD_ROOT}%{_datadir}/php

%files
%defattr(-,root,root,-)
%dir %{_datadir}/php/%{composer_vendor}/Rest/Plugin/SimpleSaml
%{_datadir}/php/%{composer_vendor}/Rest/Plugin/SimpleSaml/*
%doc README.md CHANGES.md COPYING composer.json

%changelog
* Mon Feb 09 2015 François Kooman <fkooman@tuxed.net> - 0.1.1-1
- update to 0.1.1

* Mon Feb 09 2015 François Kooman <fkooman@tuxed.net> - 0.1.0-1
- initial package
