//
//  ContactManager.swift
//  swift
//
//  Created by airm1 on 11/10/24.
//

import Foundation

class ContactManager {
    func decrypt(input: DecryptRequest) async throws -> DecryptResponse {
        guard let url = URL(string: "https://reveal.daftarproperti.org/api/decrypt") else { fatalError("Missing URL") }
        
        var inputEncoded = URLComponents()
        inputEncoded.queryItems = [
            URLQueryItem(name: "signature", value: input.signature),
            URLQueryItem(name: "clientPhoneHash2x", value: input.clientPhoneHash2x),
            URLQueryItem(name: "encryptedPhoneSalt", value: input.encryptedPhoneSalt),
            URLQueryItem(name: "encryptedContact", value: input.encryptedContact),
            URLQueryItem(name: "listingId", value: input.listingId),
            URLQueryItem(name: "referrerId", value: input.referrerId),
            URLQueryItem(name: "timestamp", value: "\(input.timestamp ?? 1)"),
        ]
        
        var request = URLRequest(url: url)
        request.httpMethod = "POST"  // Set the HTTP method
        request.setValue("application/x-www-form-urlencoded", forHTTPHeaderField: "Content-Type")  // Set the content type
        request.httpBody = inputEncoded.query?.data(using: .utf8)  // Attach the JSON data
        
        let (data, response) = try await URLSession.shared.data(for: request)
        
        guard (response as? HTTPURLResponse)?.statusCode == 200 else { fatalError("Error while fetching data") }
        
        let decodedData = try JSONDecoder().decode(DecryptResponse.self, from: data)
        
        return decodedData
    }
}
