# Security Policy

## Supported Versions

Security fixes and updates are prioritized for the latest active release branch and recent minor versions.

| Version | Supported          |
| ------- | ------------------ |
| 1.0.x   | :white_check_mark: |
| < 1.0   | :x:                |

---

## Security Considerations & Best Practices

LibreDesigoPulse operates at the boundary between your enterprise IT network (LibreNMS) and building operational technology (OT / BAS controllers)[cite: 1]. Because native BACnet/IP does not implement application-layer encryption or authentication by default, keep the following security recommendations in mind:

- **Network Segmentation:** Restrict traffic on UDP port 47808 strictly to authorized monitoring pollers and the designated controller subnets[cite: 1]. Do not expose BACnet/IP directly to public or untrusted networks.
- **API Token Security:** Store your LibreNMS API tokens securely[cite: 1]. Avoid committing API credentials, environment configuration files, or local inventory dumps (`*.txt`) containing device IPs or internal hostnames to public repositories[cite: 1].
- **Read-Only Posture:** The standard `check_bacnet` and discovery utilities only execute `ReadProperty` calls to poll telemetry states[cite: 1]. Do not modify polling scripts to issue unvalidated BACnet write requests or overrides to production physical plant controllers without strict safeguards.

---

## Reporting a Vulnerability

We take the security of LibreDesigoPulse and the operational infrastructure it monitors seriously. If you identify a security vulnerability, please notify us responsibly rather than opening a public issue.

### How to Report

1. **GitHub Security Advisory (Recommended):** Submit a private advisory report directly via the repository's **Security** tab → **Report a vulnerability**.
2. **Direct Contact:** If private advisories are unavailable, open a confidential report by emailing the repository maintainer through the contact address listed on the GitHub profile.

### What to Include

To help us triage and resolve the issue quickly, please include:

- A clear description of the vulnerability and its potential impact (e.g., buffer handling, command injection, credential exposure).
- Affected components or scripts (e.g., `check_bacnet`, `bacnet2librenms`, OS definition YAMLs)[cite: 1].
- Reproduction steps or proof-of-concept commands (ensure any sensitive IPs, passwords, or tokens are scrubbed).
- Controller models or Python environments tested against[cite: 1].

### What to Expect

- **Acknowledgment:** We will review your submission and acknowledge receipt within 48–72 hours.
- **Assessment:** We will validate the issue, determine its severity, and keep you informed of our progress.
- **Resolution & Release:** Once a patch is confirmed and tested, a security update will be tagged along with public credit to the reporter (unless anonymity is preferred).
