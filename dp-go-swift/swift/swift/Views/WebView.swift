//
//  WebView.swift
//  swift
//
//  Created by airm1 on 11/10/24.
//

import SwiftUI
import WebKit

class WebViewCoordinator: NSObject, WKScriptMessageHandler {
    var parent: WebView
    
    init(parent: WebView) {
        self.parent = parent
    }
    
    func userContentController(_ userContentController: WKUserContentController, didReceive message: WKScriptMessage) {
        // Handle the message from JavaScript
        var input = DecryptRequest()
        if message.name == "iframeMessageHandler", let messageBody = message.body as? [String: Any] {
            if let signature = messageBody["signature"] as? String {
                input.signature = signature
            }
            if let receipt = messageBody["receipt"] as? [String: Any] {
                if let listingId = receipt["listingId"] as? String {
                    input.listingId = listingId
                }
                if let clientPhoneHash2x = receipt["clientPhoneHash2x"] as? String {
                    input.clientPhoneHash2x = clientPhoneHash2x
                }
                if let encryptedContact = receipt["encryptedContact"] as? String {
                    input.encryptedContact = encryptedContact
                }
                if let encryptedPhoneSalt = receipt["encryptedPhoneSalt"] as? String {
                    input.encryptedPhoneSalt = encryptedPhoneSalt
                }
                if let referrerId = receipt["referrerId"] as? String {
                    input.referrerId = referrerId
                }
                if let timestamp = receipt["timestamp"] as? Int {
                    input.timestamp = timestamp
                }
            }
        }
        DispatchQueue.main.async {
            self.parent.input = input
        }
    }
}

struct WebView: UIViewRepresentable {
    @Binding var input: DecryptRequest
    let urlString: String
    
    func makeCoordinator() -> WebViewCoordinator {
        WebViewCoordinator(parent: self)
    }
    
    func makeUIView(context: Context) -> WKWebView {
        let configuration = WKWebViewConfiguration()
        let contentController = WKUserContentController()
        
        // Add the message handler
        contentController.add(context.coordinator, name: "iframeMessageHandler")
        
        // Example: Inject JavaScript that communicates with Swift
        let script = """
            window.addEventListener("message", function(event) {
                if (event.origin !== "https://reveal.daftarproperti.org") return;
                window.webkit.messageHandlers.iframeMessageHandler.postMessage(event.data);
            }, false);
            """
        let userScript = WKUserScript(source: script, injectionTime: .atDocumentEnd, forMainFrameOnly: false)
        contentController.addUserScript(userScript)
        
        configuration.userContentController = contentController
        
        let webView = WKWebView(frame: .zero, configuration: configuration)
        return webView
    }
    
    func updateUIView(_ webView: WKWebView, context: Context) {
        if let url = URL(string: urlString) {
            let request = URLRequest(url: url)
            webView.load(request)
        }
    }
}

#Preview {
    @Previewable @State var input: DecryptRequest = DecryptRequest()
    WebView(input: $input, urlString: "https://www.example.com")
}
