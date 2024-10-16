//
//  ListingManager.swift
//  swift
//
//  Created by airm1 on 07/10/24.
//

import Foundation
import CoreLocation

class ListingManager {
    func fetch(input: ListingRequest) async throws -> ListingResponse {
        guard let url = URL(string: "http://localhost:8080/api/listings") else { fatalError("Missing URL") }
        
        guard let jsonInput = try? JSONEncoder().encode(input) else { fatalError("Error encoding data") }
        
        var request = URLRequest(url: url)
        request.httpMethod = "POST"  // Set the HTTP method
        request.setValue("application/json", forHTTPHeaderField: "Content-Type")  // Set the content type
        request.httpBody = jsonInput  // Attach the JSON data
        
        let (data, response) = try await URLSession.shared.data(for: request)
        
        guard (response as? HTTPURLResponse)?.statusCode == 200 else { fatalError("Error while fetching data") }
        
        let decodedData = try JSONDecoder().decode(ListingResponse.self, from: data)
        
        return decodedData
    }
}
